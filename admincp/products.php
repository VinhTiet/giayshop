<?php
require("./db/connect.php");

// ===== Validate đầu vào cơ bản =====
$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$summary     = trim($_POST['summary'] ?? '');
$price       = isset($_POST['price']) ? (float)$_POST['price'] : 0;
$discount    = isset($_POST['discount']) ? (float)$_POST['discount'] : 0;
$category    = isset($_POST['category']) ? (int)$_POST['category'] : 0;
$brand       = isset($_POST['brand']) ? (int)$_POST['brand'] : 0;

if ($name === '' || $category <= 0 || $brand <= 0) {
    die("Vui lòng nhập đầy đủ: Tên, Danh mục, Thương hiệu.");
}
if ($price < 0 || $discount < 0) {
    die("Giá và giảm giá không được âm.");
}
if ($discount > $price && $price > 0) {
    // Cho phép bằng 0 nếu bạn dùng discount là giá thực tế.
    // Ở site của bạn discount đang là “giá bán thực tế”, nên có thể bỏ kiểm tra này nếu muốn.
    // die("Giá khuyến mãi không hợp lệ.");
}

// ===== Sinh slug duy nhất từ tên =====
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
$original_slug = $slug;
$counter = 1;

$sql_check_slug = "SELECT COUNT(*) FROM product WHERE slug = ?";
$stmt_check_slug = $conn->prepare($sql_check_slug);
if (!$stmt_check_slug) die("Lỗi prepare: " . $conn->error);

do {
    $stmt_check_slug->bind_param("s", $slug);
    $stmt_check_slug->execute();
    $stmt_check_slug->bind_result($count);
    $stmt_check_slug->fetch();
    if ($count > 0) {
        $slug = $original_slug . '-' . $counter;
        $counter++;
    }
} while ($count > 0);
$stmt_check_slug->close();

// ===== Upload nhiều ảnh =====
$allPaths = []; // danh sách ảnh đã lưu
if (!empty($_FILES['images']['name'][0])) {
    $totalFiles = count($_FILES['images']['name']);
    for ($i = 0; $i < $totalFiles; $i++) {
        if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
            $tmp  = $_FILES['images']['tmp_name'][$i];
            $nameFile = basename($_FILES['images']['name'][$i]);
            $ext  = strtolower(pathinfo($nameFile, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                die("Chỉ hỗ trợ JPG, JPEG, PNG, WEBP.");
            }
            $safe = preg_replace('/[^a-zA-Z0-9_\-]/','-', pathinfo($nameFile, PATHINFO_FILENAME));
            $new  = $safe . '-' . time() . '-' . $i . '.' . $ext;

            // thư mục lưu (giữ nguyên cấu trúc của bạn nếu đã có)
            $uploadDir = __DIR__ . "/uploads/";
            if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }

            $absPath = $uploadDir . $new;
            if (!move_uploaded_file($tmp, $absPath)) {
                die("Lỗi khi tải ảnh lên. Vui lòng thử lại.");
            }
            $relPath = "uploads/" . $new; // đường dẫn lưu trong DB
            $allPaths[] = $relPath;
        }
    }
}

// Nếu bạn vẫn đang submit bằng name="thumbnail[]" thay vì name="images[]", fallback:
if (empty($allPaths) && !empty($_FILES['thumbnail']['name'][0])) {
    $countfiles = count($_FILES['thumbnail']['name']);
    for ($i = 0; $i < $countfiles; $i++) {
        if ($_FILES['thumbnail']['error'][$i] === UPLOAD_ERR_OK) {
            $tmp  = $_FILES['thumbnail']['tmp_name'][$i];
            $nameFile = basename($_FILES['thumbnail']['name'][$i]);
            $ext  = strtolower(pathinfo($nameFile, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                die("Chỉ hỗ trợ JPG, JPEG, PNG, WEBP.");
            }
            $safe = preg_replace('/[^a-zA-Z0-9_\-]/','-', pathinfo($nameFile, PATHINFO_FILENAME));
            $new  = $safe . '-' . time() . '-' . $i . '.' . $ext;

            $uploadDir = __DIR__ . "/uploads/";
            if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }

            $absPath = $uploadDir . $new;
            if (!move_uploaded_file($tmp, $absPath)) {
                die("Lỗi khi tải ảnh lên. Vui lòng thử lại.");
            }
            $relPath = "uploads/" . $new;
            $allPaths[] = $relPath;
        }
    }
}

// Ghép danh sách ảnh bằng dấu “;” (giữ tương thích cột thumbnail hiện tại)
$thumbConcat = implode(';', $allPaths);

// ===== Thêm product =====
$sql_product = "INSERT INTO product
    (category_id, brand_id, name, slug, price, discount, thumbnail, description, summary, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = $conn->prepare($sql_product);
if (!$stmt) die("Lỗi prepare insert product: " . $conn->error);

$stmt->bind_param(
    "iissddsss",
    $category,
    $brand,
    $name,
    $slug,
    $price,
    $discount,
    $thumbConcat,  // lưu tất cả ảnh; ảnh đầu tiên là đại diện
    $description,
    $summary
);

if (!$stmt->execute()) {
    die("Lỗi thêm sản phẩm: " . $stmt->error);
}
$product_id = $stmt->insert_id;
$stmt->close();

/* =======================
   TẠO SIZE SẴN (stock = 0)
   ======================= */

// ❶ Bộ size mẫu
$SIZE_PRESETS = [
    'VN_39_45' => range(39, 45),
    'NU_34_42' => [34, 35, 36, 37, 38, 39, 40, 41, 42],
    'ONE'      => ['FS'], // Free Size
];

// ❷ Lấy input size mới (đúng với form addproducts.php đã chỉnh)
$size_preset  = $_POST['size_preset']  ?? '';
$custom_sizes = trim($_POST['custom_sizes'] ?? '');

// ❸ Lấy luôn input sizes[] cũ nếu bạn vẫn còn dùng form cũ
$sizes_old = $_POST['sizes'] ?? []; // nhưng sẽ lưu stock=0

// Ưu tiên custom_sizes > size_preset > sizes_old
$sizes_final = [];
if ($custom_sizes !== '') {
    $parts = array_map('trim', explode(',', $custom_sizes));
    $sizes_final = array_values(array_filter($parts, fn($s) => $s !== ''));
} elseif (isset($SIZE_PRESETS[$size_preset])) {
    $sizes_final = $SIZE_PRESETS[$size_preset];
} elseif (!empty($sizes_old)) {
    foreach ($sizes_old as $sz) {
        $sz = trim($sz);
        if ($sz !== '') $sizes_final[] = $sz;
    }
}

// ❹ Tạo dòng size (stock = 0). Khuyến nghị đã có UNIQUE (product_id, size)
if (!empty($sizes_final)) {
    $stmtSize = $conn->prepare("INSERT IGNORE INTO product_size (product_id, size, stock) VALUES (?, ?, 0)");
    foreach ($sizes_final as $sz) {
        $stmtSize->bind_param("is", $product_id, $sz);
        $stmtSize->execute();
    }
    $stmtSize->close();
}

// ===== DONE =====
header("Location: listproducts.php");
exit;
