<?php
session_start();

// Kiểm tra quyền của người dùng
if (!isset($_SESSION['admin']['type']) || $_SESSION['admin']['type'] != 'Admin') {
    $_SESSION['message'] = "Bạn Không Có Quyền Xóa Nội Dung Này";
    header("Location: listbrands.php");
    exit();
}

$delid = $_GET['id'];
require("./db/connect.php");

// Thực hiện truy vấn xóa thương hiệu
$sql_str = "DELETE FROM brand WHERE id=$delid";
if (mysqli_query($conn, $sql_str)) {
    $_SESSION['message'] = "Xóa thương hiệu thành công!";
} else {
    $_SESSION['message'] = "Không thể xóa thương hiệu. Vui lòng thử lại sau.";
}

header("Location: listbrands.php");
mysqli_close($conn);
exit();
?>
