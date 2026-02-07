<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require("./db/connect.php");

// Hiển thị lỗi khi dev (có thể tắt trong production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: loginform.php");
  exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
  $_SESSION['error_message'] = "Vui lòng nhập email và mật khẩu.";
  header("Location: loginform.php"); exit;
}

// ✅ Truy vấn đúng bảng 'admin' (chữ thường như schema của bạn)
$sql  = "SELECT id, name, email, password, type, status FROM admin WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  $_SESSION['error_message'] = "Lỗi hệ thống: " . $conn->error;
  header("Location: loginform.php"); exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  $_SESSION['error_message'] = "Tài khoản hoặc mật khẩu không đúng.";
  header("Location: loginform.php"); exit;
}

$admin = $res->fetch_assoc();

// Kiểm tra trạng thái (nếu bạn dùng enum Active/Inactive)
if (isset($admin['status']) && $admin['status'] !== 'Active') {
  $_SESSION['error_message'] = "Tài khoản đã bị khóa.";
  header("Location: loginform.php"); exit;
}

// Xác thực mật khẩu
if (!password_verify($password, $admin['password'])) {
  $_SESSION['error_message'] = "Tài khoản hoặc mật khẩu không đúng.";
  header("Location: loginform.php"); exit;
}

// ✅ Đăng nhập thành công → set session
session_regenerate_id(true); // chống fixation
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_id']        = (int)$admin['id'];
$_SESSION['admin_name']      = $admin['name'];
$_SESSION['admin_email']     = $admin['email'];
$_SESSION['admin_type']      = $admin['type']; // Admin/Staff
// Giữ nguyên mảng đầy đủ (nếu bạn đang dùng ở nơi khác)
$_SESSION['admin']           = $admin;

// (Tuỳ chọn) Remember me sơ bộ bằng cookie (chỉ demo, nên dùng token thật nếu cần)
// if (!empty($_POST['remember'])) {
//   setcookie('admin_email', $email, time()+60*60*24*30, '/');
// }

header("Location: index.php");
exit;
