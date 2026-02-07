<?php
session_start();
// Kiểm tra quyền của người dùng
if ($_SESSION['admin']['type'] != 'Admin') {
    $_SESSION['message'] = "Bạn Không Có Quyền Xóa Nội Dung Này";
    header("Location: listnews.php");
    exit();
}

$delid = $_GET['id'];
require("./db/connect.php");

// Lấy đường dẫn hình ảnh
$sqli = "SELECT thumbnails FROM news WHERE id=$delid";
$rs = mysqli_query($conn, $sqli);

if ($rs && mysqli_num_rows($rs) > 0) {
    $row = mysqli_fetch_assoc($rs);
    $img = $row['thumbnails'];

    // Xóa hình ảnh
    if (file_exists($img)) {
        unlink($img);
    }

    // Xóa bài viết
    $sql_str = "DELETE FROM news WHERE id=$delid";
    if (mysqli_query($conn, $sql_str)) {
        $_SESSION['message'] = "Xóa bài viết thành công!";
    } else {
        $_SESSION['message'] = "Không thể xóa bài viết. Vui lòng thử lại sau.";
    }
} else {
    $_SESSION['message'] = "ID bài viết không hợp lệ.";
}

header("Location: listnews.php");
mysqli_close($conn);
exit();
?>