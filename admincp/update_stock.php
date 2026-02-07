<?php
session_start(); // Khởi động session để lưu thông báo
require("./db/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $size_id = intval($_POST['size_id']);
    $stock = intval($_POST['stock']);

    // Kiểm tra dữ liệu hợp lệ
    if (!isset($_POST['size_id'], $_POST['stock']) || $stock < 0) {
        $_SESSION['error_message'] = "Dữ liệu không hợp lệ. Vui lòng thử lại.";
        header("Location: inventory.php");
        exit;
    }

    // Chuẩn bị câu lệnh cập nhật
    $sql = "UPDATE product_size SET stock = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $_SESSION['error_message'] = "Không thể chuẩn bị câu lệnh: " . $conn->error;
        header("Location: inventory.php");
        exit;
    }

    $stmt->bind_param("ii", $stock, $size_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Cập nhật thành công!";
        } else {
            $_SESSION['error_message'] = "Không tìm thấy sản phẩm với ID đã chọn.";
        }
    } else {
        $_SESSION['error_message'] = "Lỗi: " . $stmt->error;
    }

    $stmt->close();
    header("Location: inventory.php");
    exit;
}

header("Location: inventory.php");
exit;
?>