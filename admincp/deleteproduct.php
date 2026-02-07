<?php
session_start();

if (!isset($_SESSION['admin']['type']) || $_SESSION['admin']['type'] != 'Admin') {
    $_SESSION['message'] = "Bạn Không Có Quyền Xóa Nội Dung Này";
    header("Location: listproducts.php");
    exit();
}

$delid = $_GET['id'];
require("./db/connect.php");

// Lấy thông tin ảnh sản phẩm
$sqli = "SELECT thumbnail FROM product WHERE id=$delid";
$rs = mysqli_query($conn, $sqli);

if ($rs && mysqli_num_rows($rs) > 0) {
    $row = mysqli_fetch_assoc($rs);
    $thumbnail_arr = explode(';', $row['thumbnail']);

    // Xóa các file ảnh liên quan
    foreach ($thumbnail_arr as $img) {
        if (file_exists($img)) {
            unlink($img);
        }
    }

    // Xóa các bản ghi liên quan trong bảng product_size
    $deleteSizeSQL = "DELETE FROM product_size WHERE product_id=$delid";
    if (!mysqli_query($conn, $deleteSizeSQL)) {
        $_SESSION['message'] = "Lỗi khi xóa kích cỡ sản phẩm. Vui lòng thử lại.";
        header("Location: listproducts.php");
        mysqli_close($conn);
        exit();
    }

    // Xóa sản phẩm trong bảng product
    $sql_str = "DELETE FROM product WHERE id=$delid";
    if (mysqli_query($conn, $sql_str)) {
        $_SESSION['message'] = "Xóa sản phẩm thành công!";
    } else {
        $_SESSION['message'] = "Không thể xóa sản phẩm. Vui lòng thử lại sau.";
    }
} else {
    $_SESSION['message'] = "ID sản phẩm không hợp lệ.";
}

header("Location: listproducts.php");
mysqli_close($conn);
exit();
?>