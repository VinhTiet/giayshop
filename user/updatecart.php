<?php
session_start();
require("./db/connect.php");

// Lấy thông tin từ yêu cầu
$idsp = intval($_GET['id']);
$newQty = intval($_POST['qty']);
$size = $_POST['size']; // Lấy kích thước từ form

// Lấy giỏ hàng từ session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Lấy số lượng tồn kho hiện tại từ bảng `product_size`
$sql_stock = "SELECT stock FROM product_size WHERE product_id = $idsp AND size = '$size'";
$result_stock = mysqli_query($conn, $sql_stock);
$product_size = mysqli_fetch_assoc($result_stock);

if (!$product_size) {
    echo "<script>alert('Sản phẩm hoặc kích thước không tồn tại!'); window.location='cart.php';</script>";
    exit();
}
// Kiểm tra số lượng tồn kho trước khi cập nhật giỏ hàng
if ($newQty > $product_size['stock']) {
    echo "<script>alert('Số lượng không đủ trong kho!'); window.location='cart.php';</script>";
    exit();
}


// Lặp qua giỏ hàng để cập nhật số lượng
for ($i = 0; $i < count($cart); $i++) {
    if ($cart[$i]['id'] == $idsp && $cart[$i]['size'] == $size) {
        // Số lượng cũ của sản phẩm trong giỏ
        $oldQty = $cart[$i]['qty'];

        // Tính toán chênh lệch số lượng
        $qtyDiff = $newQty - $oldQty;

        // Kiểm tra nếu số lượng tồn kho đủ đáp ứng
        if ($product_size['stock'] - $qtyDiff >= 0) {
            // Cập nhật số lượng sản phẩm trong giỏ
            $cart[$i]['qty'] = $newQty;

            // Cập nhật tồn kho trong bảng `product_size`
            $sql_update_stock = "UPDATE product_size SET stock = stock - $qtyDiff WHERE product_id = $idsp AND size = '$size'";
            mysqli_query($conn, $sql_update_stock);
        } else {
            // Nếu không đủ tồn kho, hiển thị thông báo
            echo "<script>alert('Số lượng không đủ trong kho!'); window.location='cart.php';</script>";
            exit();
        }

        break;
    }
}

// Cập nhật giỏ hàng trong session
$_SESSION['cart'] = $cart;

// Quay lại trang giỏ hàng
header("Location: cart.php");
exit();

?>