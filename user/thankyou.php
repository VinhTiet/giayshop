<?php
session_start();

$is_homepage = false;
require("./db/connect.php");

if (isset($_POST["atcbtn"])) {
    $id = $_POST['pid'];
    $qty = $_POST['qty'];

    $cart = [];
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }
    $isFound = false;
    for ($i = 0; $i < count($cart); $i++) {
        if ($cart[$i]['id'] == $id) {
            $cart[$i]['qty'] += $qty;
            $isFound = true;
            break;
        }
    }
    if (!$isFound) {
        $sql_str = "select * from product where id = $id";
        $result = mysqli_query($conn, $sql_str);
        $product = mysqli_fetch_assoc($result);
        $product['qty'] = $qty;
        $cart[] = $product;
    }

    $_SESSION['cart'] = $cart;
}
require_once('modules/header.php');

?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Hoàn Tất</h2>
                    <div class="breadcrumb__option">
                        <a href="index.php">Trang Chủ</a>
                        <span>Hoàn Tất</span>
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
            <h4 style="text-align: center;">Cảm Ơn Bạn Đã Đặt Hàng Của Chúng Tôi <br> Chúng Tôi Sẽ Sớm Liên Hệ Đến Bạn!
            </h4>
            <!-- </form> -->
        </div>
    </div>
</section>
<!-- Checkout Section End -->

<?php
require_once('modules/footer.php');
?>