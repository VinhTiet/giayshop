<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="./assets/css/style2.css?v=1">
</head>

<body>
    <div class="wrapper">
        <?php if (!isset($_SESSION['user'])): ?>
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
                        <input type="checkbox" id="remember">
                        <p>Lưu Mật Khẩu</p>
                    </label>
                    <a href="#">Quên Mật Khẩu?</a>
                </div>
                <?php if (isset($error_message)): ?>
                    <p class="error" style="color: #fff;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <button type="submit">Đăng Nhập</button>
                <div class="register">
                    <p>Bạn Chưa Có Tài Khoản ? <a href="register.php" style="color: aqua;">Đăng Ký Ngay</a></p>
                </div>
            </form>
        <?php endif; 
        ?>
    </div>
</body>

</html>