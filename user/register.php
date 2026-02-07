<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="./assets/css/style2.css?v=1">
</head>

<body>
    <div class="wrapper">
        <form action="reg.php" method="post">
            <h2>Đăng Ký</h2>
            <div class="input-field">
                <input type="text" name="name">
                <label>Họ Và Tên</label>
            </div>

            <div class="input-field">
                <input type="email" name="email">
                <label>Email</label>
            </div>

            <div class="input-field">
                <input type="password" name="password">
                <label>Mật Khẩu</label>
            </div>

            <div class="input-field">
                <input type="text" name="phone_number">
                <label>Số Điện Thoại</label>
            </div>

            <div class="input-field">
                <input type="text" name="address">
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
            if (isset($error_message)) {
                echo "<p style='color: #fff;'>$error_message</p>";
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