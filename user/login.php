<?php
session_start();
include("./db/connect.php");

// Hiển thị lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['email'];
    $password = $_POST['password'];

    // Query để kiểm tra username trong bảng user
    $query = "SELECT * FROM user WHERE email=?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Database query failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu sử dụng password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user; // Lưu toàn bộ thông tin user vào session
            header("Location: index.php"); // Chuyển hướng tới trang chính
            exit;
        } else {
            $error_message = "Tài khoản hoặc mật khẩu không đúng";
            require_once('loginform.php');
        }
    } else {
        $error_message = "Tài khoản hoặc mật khẩu không đúng";
        require_once('loginform.php');
    }
}

?>