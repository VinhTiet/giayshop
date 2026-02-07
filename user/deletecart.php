<?php
session_start();
require("./db/connect.php");

// Lấy ID sản phẩm và size từ URL hoặc session
$idsp = intval($_GET['id']);
$size = $_GET['size']; // Lấy kích thước từ URL

// Lấy giỏ hàng từ session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$qtyToRestore = 0;

// Duyệt qua giỏ hàng để tìm sản phẩm cần xóa
for ($i = 0; $i < count($cart); $i++) {
    if ($cart[$i]['id'] == $idsp && $cart[$i]['size'] == $size) {
        $qtyToRestore = $cart[$i]['qty']; // Lấy số lượng sản phẩm bị xóa
        array_splice($cart, $i, 1); // Xóa sản phẩm khỏi giỏ hàng
        break;
    }
}

// Cập nhật session giỏ hàng
$_SESSION['cart'] = $cart;

// Cập nhật lại số lượng tồn kho trong bảng `product_size`
$sql_update_stock = "UPDATE product_size SET stock = stock + $qtyToRestore WHERE product_id = $idsp AND size = '$size'";
$conn->query($sql_update_stock);

// Quay lại trang giỏ hàng
header("Location: cart.php");
exit();

?>