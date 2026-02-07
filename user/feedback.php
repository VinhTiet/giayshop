<?php
session_start();
$is_homepage = false;

require_once('modules/header.php');
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>VPACE</h2>
                    <div class="breadcrumb__option">
                        <a href="index.php">Trang Chủ</a>
                        <span>Liên Hệ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Contact Section Begin -->
<section class="contact spad">
    <div class="container">
        <div class="row">
            <!-- Cột 1: Biểu mẫu liên hệ -->
            <div class="col-lg-6 col-md-6">
                <div class="contact__form">
                    <h3 style="margin-bottom: 10px;">Gửi Phản Hồi Cho Chúng Tôi</h3>
                    <form action="process_contact.php" method="POST">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Tên của bạn" name="name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Email" name="email" required>
                        </div>
                        <div class="mb-3">

                            <input type="text" class="form-control" placeholder="Số Điện Thoại" name="phone_number"
                                required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" placeholder="Nội dung tin nhắn" name="note" rows="5"
                                required></textarea>
                        </div>
                        <button type="submit" class="site-btn" style="font-weight: 500" ;>Gửi</button>
                    </form>
                </div>
            </div>
            <!-- Cột 2: Thông tin liên hệ -->
            <div class="col-lg-6 col-md-6">
                <div class="contact__text">
                    <h3 style="margin-bottom: 10px;">Thông Tin Liên Hệ</h3>
                    <div class="contact__address">
                        <h5>Địa Chỉ:</h5>
                        <p>368A Cần Thơ</p>
                    </div>
                    <div class="contact__address">
                        <h5>Email:</h5>
                        <p>VPACE@gmai.com</p>
                    </div>
                    <div class="contact__address">
                        <h5>Điện Thoại:</h5>
                        <p>+84 123 456 789</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<?php
require_once('modules/footer.php');
?>