<?php
// BẮT BUỘC: mở session trước khi dùng $_SESSION
if (session_status() === PHP_SESSION_NONE) session_start();

// Nếu đã đăng nhập thì về dashboard
if (!empty($_SESSION['admin_logged_in'])) {
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Nhập</title>
  <link rel="stylesheet" href="./assets/css/style2.css?v=0">
</head>
<body>
  <div class="wrapper">
    <form action="login.php" method="POST" id="loginForm">
      <h2>Đăng Nhập</h2>

      <div class="input-field">
        <input type="email" name="email" id="email" required>
        <label>Email</label>
      </div>

      <div class="input-field">
        <input type="password" name="password" id="password" required>
        <label>Mật Khẩu</label>
      </div>

      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember" name="remember">
          <p>Lưu Mật Khẩu</p>
        </label>
        <a href="#">Quên Mật Khẩu?</a>
      </div>

      <?php if (!empty($_SESSION['error_message'])): ?>
        <p style="color:red;"><?= $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>

      <button type="submit">Đăng Nhập</button>
      <div class="register"></div>
    </form>
  </div>
</body>
</html>
