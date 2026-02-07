<?php
session_start();

if (!isset($_SESSION['admin']['type']) || $_SESSION['admin']['type'] != 'Admin') {
    $_SESSION['message'] = "Bạn Không Có Quyền Xóa Nội Dung Này";
    header("Location: listcate.php");
    exit();
}

$delid = $_GET['id'];
require("./db/connect.php");

$sql_str = "DELETE FROM category WHERE id=$delid";
if (mysqli_query($conn, $sql_str)) {
    $_SESSION['message'] = "Xóa danh mục thành công!";
} else {
    $_SESSION['message'] = "Không thể xóa danh mục. Vui lòng thử lại sau.";
}

header("Location: listcate.php");
mysqli_close($conn);
exit();
?>