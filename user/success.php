<?php
session_start();
$is_homepage = false;
require("./db/connect.php");
// Kiểm tra xem biến session tồn tại và có giá trị true không
if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {


    // Xóa biến session để không hiển thị lại thông báo khi người dùng làm mới trang
    unset($_SESSION['registration_success']);
}
require_once('modules/header.php');
?>

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="./assets/img/banner2.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Vũ Vinh Music</h2>
                        <div class="breadcrumb__option">
                            <a href="index.php">Trang Chủ</a>
                            <span>Đăng Kí Thành Công</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            
            <div class="checkout__form">
                <h4 style="text-align: center;">Bạn Đã Đăng Kí Thành Công!<br>Vui Lòng Trở Về Trang Chủ.</h4>
                <!-- </form> -->
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

<?php 
    require_once('modules/footer.php');
?>