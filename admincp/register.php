<?php
session_start();
?>
<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Ký</title>
  <link rel="stylesheet" href="./assets/css/style2.css?v=1.0">
</head>
<body>
  <div class="wrapper">
    <form action="reg.php" method="post">
      <h2>Đăng Ký</h2>
      <div class="input-field">
        <input type="text" name="name" required>
        <label>Họ Và Tên</label>
      </div>
      <div class="input-field">
        <input type="email" name="email" required>
        <label>Email</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" required>
        <label>Mật Khẩu</label>
      </div>
      <div class="input-field">
        <input type="text" name="phone_number" required>
        <label>Số Điện Thoại</label>
      </div>
      <div class="input-field">
        <input type="text" name="address" required>
        <label>Địa Chỉ</label>
      </div>
      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember">
          <p>Lưu Mật Khẩu</p>
        </label>
        <a href="#">Quên Mật Khẩu?</a>
 
      </div>
      <?php
    // Hiển thị thông báo lỗi nếu có
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color:red;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']); // Xóa thông báo lỗi sau khi hiển thị
    }
    ?>
      <button type="submit">Đăng Ký</button>
      <div class="register">
        <p>Bạn Đã Có Tài Khoản? <a href="loginform.php" style="color: aqua;">Đăng Nhập</a></p>
      </div>
    </form>
    
    
  </div>
</body>
</html>