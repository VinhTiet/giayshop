<?php
session_start();
$is_homepage = false;
require ("./db/connect.php");
// Kiểm tra xem biểu mẫu đã được gửi chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $note = $_POST["note"];

    // Kiểm tra xem các trường có được điền đầy đủ hay không
    if (!empty($name) && !empty($email) && !empty($phone_number) && !empty($note)) {
        // Xử lý dữ liệu ở đây (ví dụ: lưu vào cơ sở dữ liệu, gửi email thông báo, vv.)
        // Ví dụ lưu vào cơ sở dữ liệu
        // Đảm bảo rằng bạn đã thiết lập kết nối cơ sở dữ liệu trước
        require ("./db/connect.php");

        // Chuẩn bị truy vấn SQL để chèn dữ liệu vào cơ sở dữ liệu
        $sql = "INSERT INTO feedback (name, email, phone_number, note, created_at) VALUES ('$name', '$email', '$phone_number', '$note', now())";

        // Thực thi truy vấn
        if (mysqli_query($conn, $sql)) {
           
        } else {
            echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
        }
   
        // Đóng kết nối
        mysqli_close($conn);
    } else {
        echo "Vui lòng điền đầy đủ thông tin trong biểu mẫu liên hệ.";
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}
require_once ('modules/header.php');
?>
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="./assets/img/banner2.png">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Hoàn Tất</h2>
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
  <!-- Checkout Section Begin -->
  <section class="checkout spad">
        <div class="container">
            
            <div class="checkout__form">
                <h4 style="text-align: center;">Thông Điệp Của Bạn Đã Gửi Thành Công!<br>Cảm Ơn Bạn Đã Phản Hồi.</h4>
                <!-- </form> -->
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
<?php
require_once ('modules/footer.php');
?>