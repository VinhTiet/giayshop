<?php
session_start();
include ("./db/connect.php");

// Hiển thị lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra xem email đã tồn tại chưa
    $query = "SELECT * FROM user WHERE email=?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Database query failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Email đã được sử dụng.";
        require_once ('register.php');
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $query = "INSERT INTO user (name, email, password, phone_number, address, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'Active', NOW(), NOW())";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Database query failed: " . $conn->error);
        }

        $stmt->bind_param("sssss", $name, $email, $hashed_password, $phone_number, $address);

        if ($stmt->execute()) {
            // Lấy ID của người dùng mới đăng ký
            $user_id = $stmt->insert_id;

            // Lấy thông tin người dùng từ cơ sở dữ liệu
            $query = "SELECT * FROM user WHERE id=?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                die("Database query failed: " . $conn->error);
            }

            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Lưu thông tin người dùng vào session
            $_SESSION['user'] = $user;

            // Thiết lập biến session để đánh dấu rằng đăng ký đã thành công
            $_SESSION['registration_success'] = true;

            header("Location: success.php?registration_success=true"); // Chuyển hướng tới trang chính
            exit;
        } else {
            $error_message = "Đăng ký không thành công. Vui lòng thử lại.";
            require_once ('register.php');
        }
    }
}
?>
