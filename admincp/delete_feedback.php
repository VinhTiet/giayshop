<?php
session_start();
$delid = $_GET['id'];
require ("./db/connect.php");

// Kiểm tra xem người dùng có quyền admin hay không
if ($_SESSION['admin']['type'] != 'Admin') {
    $_SESSION['message'] = "Bạn Không Có Quyền Xóa Nội Dung Này";
    header("Location: feedback.php");
    exit();
} else {
    // Kiểm tra xem id có tồn tại và hợp lệ
    if (isset($delid) && is_numeric($delid)) {
        $sql_str = "DELETE FROM feedback WHERE id=$delid";
        if (mysqli_query($conn, $sql_str)) {
            $_SESSION['message'] = "Xóa sản phẩm thành công!";
        } else {
            $_SESSION['message'] = "Không thể xóa sản phẩm. Vui lòng thử lại sau.";
        }
    } else {
        $_SESSION['message'] = "ID sản phẩm không hợp lệ.";
    }
    header("Location: feedback.php");
    exit();
}
mysqli_close($conn);
?>