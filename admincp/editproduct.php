<?php
require("./db/connect.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  die("Thiếu ID sản phẩm.");
}

/* ============ LẤY DỮ LIỆU SẢN PHẨM ============ */
$sql = "SELECT p.id AS pid, p.name AS pname, p.slug, p.description, p.summary,
               p.price, p.discount, p.thumbnail, c.id AS cid, c.name AS cname,
               b.id AS bid, b.name AS bname
        FROM product p
        JOIN category c ON p.category_id = c.id
        JOIN brand b    ON p.brand_id    = b.id
        WHERE p.id = ?";
$st = $conn->prepare($sql);
$st->bind_param("i", $id);
$st->execute();
$product = $st->get_result()->fetch_assoc();
if (!$product) { die("Không tìm thấy sản phẩm."); }

/* Size hiện có (read-only ở form) */
$sql_sizes = "SELECT id AS size_id, size FROM product_size WHERE product_id = ? ORDER BY size";
$st2 = $conn->prepare($sql_sizes);
$st2->bind_param("i", $id);
$st2->execute();
$rs_sizes = $st2->get_result();
$sizes = [];
while ($r = $rs_sizes->fetch_assoc()) { $sizes[] = $r; }

/* ============ SUBMIT ============ */
if (isset($_POST['btnUpdate'])) {
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
    die("Giá/giảm giá không hợp lệ.");
  }

  /* Slug duy nhất (trừ chính nó) */
  $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
  $orig = $slug; $k = 1;

  $stSlug = $conn->prepare("SELECT COUNT(*) FROM product WHERE slug = ? AND id <> ?");
  do {
    $stSlug->bind_param("si", $slug, $id);
    $stSlug->execute();
    $stSlug->bind_result($cnt);
    $stSlug->fetch();
    if ($cnt > 0) { $slug = $orig . '-' . $k++; }
  } while ($cnt > 0);
  $stSlug->close();

  /* ẢNH: nếu upload mới -> thay ảnh; nếu không -> giữ cũ */
  $keepOldThumbnails = true;
  $thumbConcat = $product['thumbnail']; // mặc định giữ cũ
  $relBase = "uploads/";
  $uploadDir = __DIR__ . "/uploads/";

  if (!empty($_FILES['thumbnail']['name'][0])) {
    if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }
    $allPaths = [];
    $totalFiles = count($_FILES['thumbnail']['name']);

    for ($i=0; $i<$totalFiles; $i++) {
      if ($_FILES['thumbnail']['error'][$i] === UPLOAD_ERR_OK) {
        $tmp  = $_FILES['thumbnail']['tmp_name'][$i];
        $nameFile = basename($_FILES['thumbnail']['name'][$i]);
        $ext  = strtolower(pathinfo($nameFile, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
          die("Chỉ hỗ trợ JPG, JPEG, PNG, WEBP.");
        }
        $safe = preg_replace('/[^a-zA-Z0-9_\-]/','-', pathinfo($nameFile, PATHINFO_FILENAME));
        $new  = $safe . '-' . time() . '-' . $i . '.' . $ext;
        $absPath = $uploadDir . $new;

        if (!move_uploaded_file($tmp, $absPath)) {
          die("Upload ảnh thất bại.");
        }
        $allPaths[] = $relBase . $new;
      }
    }

    if (!empty($allPaths)) {
      // Xóa ảnh cũ (nếu tồn tại)
      if (!empty($product['thumbnail'])) {
        $oldArr = explode(';', $product['thumbnail']);
        foreach ($oldArr as $old) {
          $toDel = __DIR__ . '/' . $old;
          if (is_file($toDel)) { @unlink($toDel); }
        }
      }
      $thumbConcat = implode(';', $allPaths);
      $keepOldThumbnails = false;
    }
  }

  /* Cập nhật product */
/* Cập nhật product */
$up = $conn->prepare(
  "UPDATE product
     SET name = ?, slug = ?, description = ?, summary = ?,
         price = ?, discount = ?, thumbnail = ?, category_id = ?, brand_id = ?, updated_at = NOW()
   WHERE id = ?"
);

$up->bind_param(
  "ssssddsiii",
  $name,        // s
  $slug,        // s
  $description, // s
  $summary,     // s
  $price,       // d
  $discount,    // d
  $thumbConcat, // s
  $category,    // i
  $brand,       // i
  $id           // i
);

if (!$up->execute()) {
  die("Lỗi cập nhật: " . $up->error);
}
$up->close();


  /* ====== THÊM SIZE MỚI (stock = 0) ====== */
  $SIZE_PRESETS = [
    'VN_35_45' => range(35, 45),
    'NU_36_40' => [36, 37, 38, 39, 40],
    'ONE'      => ['FS'],
  ];
  $size_preset  = $_POST['size_preset']  ?? '';
  $custom_sizes = trim($_POST['custom_sizes'] ?? '');
  $add_sizes    = $_POST['add_sizes'] ?? []; // tùy chọn (nếu có field)

  $toCreate = [];
  if ($custom_sizes !== '') {
    $parts = array_map('trim', explode(',', $custom_sizes));
    foreach ($parts as $s) if ($s !== '') $toCreate[] = $s;
  } elseif (isset($SIZE_PRESETS[$size_preset])) {
    $toCreate = $SIZE_PRESETS[$size_preset];
  } elseif (!empty($add_sizes)) {
    foreach ($add_sizes as $s) {
      $s = trim($s);
      if ($s !== '') $toCreate[] = $s;
    }
  }

  if (!empty($toCreate)) {
    // cần có UNIQUE(product_id, size) để tránh trùng
    $ins = $conn->prepare("INSERT IGNORE INTO product_size (product_id, size, stock) VALUES (?, ?, 0)");
    foreach ($toCreate as $sz) {
      $ins->bind_param("is", $id, $sz);
      $ins->execute();
    }
    $ins->close();
  }

  header("Location: listproducts.php");
  exit;
}

/* ============ VIEW FORM ============ */
require('./modules/header.php');
if (!isset($_SESSION['admin']) || $_SESSION['admin']['type'] != 'Admin') {
  echo "<h3>Bạn Không Có Quyền Chỉnh Sửa Nội Dung Này</h3>";
  require('./modules/footer.php');
  exit;
}
?>

<div>
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <div class="row">
          <div class="col-lg-12">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Cập Nhật Sản Phẩm</h1>
              </div>

              <form class="user" method="post" action="#" enctype="multipart/form-data">
                <div class="form-group">
                  <label class="form-label">Tên Sản Phẩm</label>
                  <input type="text" class="form-control form-control-user" id="name" name="name"
                         placeholder="Tên Sản Phẩm" value="<?= htmlspecialchars($product['pname']) ?>" required>
                </div>

                <div class="form-group">
                  <label class="form-label">Tóm Tắt Sản Phẩm</label>
                  <textarea name="summary" class="form-control" rows="3"><?= htmlspecialchars($product['summary']) ?></textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Mô Tả Sản Phẩm</label>
                  <textarea name="description" class="form-control" rows="6"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <div class="form-group row">
                  <div class="col-sm-6 mb-sm-0">
                    <label class="form-label">Giá Gốc</label>
                    <input type="number" class="form-control form-control-user" name="price" id="price"
                           value="<?= (float)$product['price'] ?>" min="0" required>
                  </div>
                  <div class="col-sm-6 mb-sm-0">
                    <label class="form-label">Giá Khuyến Mãi</label>
                    <input type="number" class="form-control form-control-user" name="discount" id="discount"
                           value="<?= (float)$product['discount'] ?>" min="0">
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Kích thước hiện có (read-only)</label>
                  <table class="table table-sm">
                    <thead><tr><th>Size</th></tr></thead>
                    <tbody>
                      <?php if (empty($sizes)): ?>
                        <tr><td><i>Chưa có size nào.</i></td></tr>
                      <?php else: foreach ($sizes as $s): ?>
                        <tr><td><?= htmlspecialchars($s['size']) ?></td></tr>
                      <?php endforeach; endif; ?>
                    </tbody>
                  </table>
                  <small class="text-muted">
                    * Tồn kho không chỉnh ở đây. Hệ thống tính tự động từ <b>Phiếu nhập</b> và đơn <b>Đã giao</b>.
                  </small>
                </div>

                <div class="form-group">
                  <label class="form-label">Thêm size mới (stock = 0)</label>
                  <select name="size_preset" class="form-control">
                    <option value="">— Không dùng preset —</option>
                    <option value="VN_35_45">39–45</option>
                    <option value="NU_36_40">35–42</option>
                    <option value="ONE">Một size (FS)</option>
                  </select>
                  <small class="text-muted d-block mb-2">Hoặc tự nhập danh sách size (ưu tiên hơn preset):</small>
                  <input type="text" name="custom_sizes" class="form-control" placeholder="VD: 35,36,37,38 hoặc S,M,L,XL">
                </div>

                <div class="form-group">
                  <label class="form-label">Ảnh Sản Phẩm (chọn nhiều để thay thế ảnh cũ)</label>
                  <input type="file" class="form-control" name="thumbnail[]" id="thumbnail" multiple accept=".jpg,.jpeg,.png,.webp">
                  <div class="mt-2">
                    Ảnh hiện tại:
                    <?php
                      if (!empty($product['thumbnail'])) {
                        $arr = explode(';', $product['thumbnail']);
                        foreach ($arr as $img) {
                          echo "<img src='".htmlspecialchars($img)."' height='90' style='margin-right:8px;border:1px solid #ddd;padding:2px;border-radius:4px' />";
                        }
                      } else {
                        echo "<i>Chưa có ảnh.</i>";
                      }
                    ?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Danh Mục</label>
                  <select class="form-control" name="category" id="category" required>
                    <option value="">Chọn Danh Mục</option>
                    <?php
                      $rsC = mysqli_query($conn, "SELECT id,name FROM category ORDER BY name");
                      while ($row = mysqli_fetch_assoc($rsC)) {
                        $sel = ($row['id'] == $product['cid']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' {$sel}>".htmlspecialchars($row['name'])."</option>";
                      }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Thương Hiệu</label>
                  <select class="form-control" name="brand" id="brand" required>
                    <option value="">Chọn Thương Hiệu</option>
                    <?php
                      $rsB = mysqli_query($conn, "SELECT id,name FROM brand ORDER BY name");
                      while ($row = mysqli_fetch_assoc($rsB)) {
                        $sel = ($row['id'] == $product['bid']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' {$sel}>".htmlspecialchars($row['name'])."</option>";
                      }
                    ?>
                  </select>
                </div>

                <button class="btn btn-primary" name="btnUpdate">Cập Nhật</button>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require('./modules/footer.php'); ?>
