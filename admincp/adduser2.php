<?php
session_start();
require('./db/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $type = $_POST['type'];

    // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
    $sql_check_email = "SELECT * FROM `admin` WHERE `email` = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        $_SESSION['error_message'] = "Email đã tồn tại trong hệ thống.";
        header("location: adduser.php"); // Chuyển hướng về trang thêm người dùng
        exit;
    }

    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Thêm người dùng mới vào cơ sở dữ liệu
    $sql_str = "INSERT INTO `admin` (`name`, `email`, `password`, `phone_number`, `address`, `type`, `status`, `created_at`, `updated_at`) 
                VALUES (?, ?, ?, ?, ?, ?, 'Active', NOW(), NOW())";

    $stmt = $conn->prepare($sql_str);
    if (!$stmt) {
        $_SESSION['error_message'] = "Lỗi trong quá trình chuẩn bị câu lệnh SQL.";
        header("location: adduser.php"); // Chuyển hướng về trang thêm người dùng
        exit;
    }

    $stmt->bind_param("ssssss", $name, $email, $hashed_password, $phone_number, $address, $type);

    if ($stmt->execute()) {
        // Thêm thành công, chuyển hướng về trang danh sách người dùng
        header("location: listuser.php");
        exit;
    } else {
        // Lỗi khi thêm vào cơ sở dữ liệu
        $_SESSION['error_message'] = "Thêm người dùng không thành công.";
        header("location: adduser.php"); // Chuyển hướng về trang thêm người dùng
        exit;
    }

    $stmt_check_email->close();
    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error_message'] = "Phương thức không được chấp nhận.";
    header("location: adduser.php"); // Chuyển hướng về trang thêm người dùng
    exit;
}
?>
