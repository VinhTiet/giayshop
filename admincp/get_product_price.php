<?php
// admincp/get_product_price.php
require('./db/connect.php');
header('Content-Type: application/json; charset=utf-8');


$PROFIT_PERCENT = 20;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo json_encode(['ok' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

// 1) Lấy giá bán hiện tại (price/discount)
$sqlProd = "SELECT price, discount FROM product WHERE id = $id";
$rsProd  = mysqli_query($conn, $sqlProd);
if (!$rsProd || mysqli_num_rows($rsProd) == 0) {
    echo json_encode(['ok' => false, 'message' => 'Không tìm thấy sản phẩm']);
    exit;
}
$prod = mysqli_fetch_assoc($rsProd);
$price    = (float)$prod['price'];
$discount = isset($prod['discount']) ? (float)$prod['discount'] : 0;

// Giá bán dùng để tính lời: nếu có discount hợp lệ thì dùng discount, ngược lại dùng price
$sell_price = ($discount > 0 && $discount < $price) ? $discount : $price;

// 2) Nếu đã có lần nhập trước → ưu tiên lấy giá nhập lần gần nhất
$sqlLastImport = "
    SELECT unit_price 
    FROM purchase_receipt_items 
    WHERE product_id = $id
    ORDER BY created_at DESC 
    LIMIT 1
";
$rsLast = mysqli_query($conn, $sqlLastImport);

if ($rsLast && mysqli_num_rows($rsLast) > 0) {
    $rowLast     = mysqli_fetch_assoc($rsLast);
    $import_price = (float)$rowLast['unit_price'];   // giá nhập gần nhất
} else {
    // 3) Chưa từng nhập → TÍNH NGƯỢC TỪ GIÁ BÁN THEO % LỜI
    // LỜI = % trên GIÁ BÁN → import = sell * (1 - L/100)
    $import_price = 0;
    if ($sell_price > 0 && $PROFIT_PERCENT >= 0 && $PROFIT_PERCENT < 100) {
        $import_price = $sell_price * (1 - $PROFIT_PERCENT / 100);
    }
}

// Có thể làm tròn về nghìn cho đẹp (tùy thích)
// $import_price = round($import_price, -3);

echo json_encode([
    'ok'           => true,
    'sell_price'   => $sell_price,    // để tham khảo nếu cần
    'import_price' => $import_price   // dùng để fill vào ô Đơn giá
]);
