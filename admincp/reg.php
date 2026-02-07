<?php
session_start();
include("./db/connect.php"); // Đảm bảo đường dẫn đến file connect.php chính xác

// Hiển thị lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra xem email đã tồn tại chưa
    $query_check_email = "SELECT * FROM admin WHERE email=?";
    $stmt_check_email = $conn->prepare($query_check_email);
    if (!$stmt_check_email) {
        $_SESSION['error_message'] = "Lỗi trong quá trình kiểm tra email.";
        header("Location: register.php"); // Chuyển về trang đăng ký
        exit;
    }

    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        $_SESSION['error_message'] = "Email đã được sử dụng.";
        header("Location: register.php"); // Chuyển về trang đăng ký
        exit;
    }

    // Thêm người dùng mới vào cơ sở dữ liệu
    $query_insert_user = "INSERT INTO admin (name, email, password, phone_number, address, status, type, created_at, updated_at) 
                          VALUES (?, ?, ?, ?, ?, 'Active', 'Staff', NOW(), NOW())";
    $stmt_insert_user = $conn->prepare($query_insert_user);
    if (!$stmt_insert_user) {
        $_SESSION['error_message'] = "Lỗi trong quá trình chuẩn bị thêm người dùng mới.";
        header("Location: register.php"); // Chuyển về trang đăng ký
        exit;
    }

    $stmt_insert_user->bind_param("sssss", $name, $email, $hashed_password, $phone_number, $address);

    if ($stmt_insert_user->execute()) {
        // Lấy ID của người dùng mới đăng ký
        $admin_id = $stmt_insert_user->insert_id;

        // Lưu thông tin người dùng vào session
        $_SESSION['admin'] = [
            'id' => $admin_id,
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'address' => $address,
            'status' => 'Active',
            'type' => 'Staff',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Thiết lập biến session để đánh dấu rằng đăng ký đã thành công
        $_SESSION['registration_success'] = true;

        header("Location: index.php?registration_success=true"); // Chuyển hướng tới trang chính
        exit;
    } else {
        $_SESSION['error_message'] = "Đăng ký không thành công. Vui lòng thử lại.";
        header("Location: register.php"); // Chuyển về trang đăng ký
        exit;
    }

    // Đóng các statement
    $stmt_check_email->close();
    $stmt_insert_user->close();
}
?>