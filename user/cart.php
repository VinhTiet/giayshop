<?php
session_start();

$is_homepage = false;
require("./db/connect.php");

if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}

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
        $sql_str = "SELECT * FROM product WHERE id = $id";
        $result = mysqli_query($conn, $sql_str);
        $product = mysqli_fetch_assoc($result);
        $product['qty'] = $qty;
        $product['thumbnail'] = $thumbnail_arr[0]; // Lấy URL hình ảnh đầu tiên từ mảng thumbnail_arr
        $product['size'] = $size;  // Bạn cần đảm bảo rằng $size có giá trị hợp lệ
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
                    <h2>Giỏ Hàng</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Trang Chủ</a>
                        <span>Giỏ Hàng</span>
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
            <h4>Giỏ Hàng</h4>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="checkout__order">
                        <h4>Đơn Hàng Của Bạn</h4>
                        <div class="checkout__order__products">Sản Phẩm</div>
                        <table class="table">
                            <tr>
                                <th>STT</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Kích Thước</th> <!-- Cột kích thước -->
                                <th>Đơn Giá</th>
                                <th>Số Lượng</th>
                                <th>Thành Tiền</th>
                                <th></th>
                                <th></th>
                            </tr>
                            <?php
                            $count = 0;
                            $total = 0;
                            foreach ($cart as $item) {
                                $total += $item['qty'] * $item['discount'];
                                ?>
                            <form action="updatecart.php?id=<?= $item['id'] ?>" method="post">
                                <tr>
                                    <td><?= ++$count ?></td>
                                    <td><?= $item['name'] ?></td>
                                    <td><?= $item['size'] ?></td> <!-- Hiển thị kích thước -->
                                    <td><?= number_format($item['discount'], 0, '', '.') ?><sup>đ</sup></td>
                                    <td>
                                        <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1">
                                        <input type="hidden" name="size" value="<?= $item['size'] ?>">
                                        <!-- Gửi kèm size -->
                                    </td>
                                    <td><?= number_format($item['discount'] * $item['qty'], 0, '', '.') ?><sup>đ</sup>
                                    </td>
                                    <td><button class="btn btn-primary">Cập Nhật</button></td>
                                    <td><a href='deletecart.php?id=<?= $item['id'] ?>&size=<?= $item['size'] ?>'
                                            class="btn btn-danger">Xóa</a></td>
                                </tr>
                            </form>
                            <?php
                            }
                            ?>
                        </table>


                        <div class="checkout__order__total">Tổng
                            Tiền:<span><?= number_format($total, 0, '', '.') ?><sup>đ</sup></span></div>
                        <div class="d-flex justify-content-between">
                            <a href="shop.php" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
                            <a href="checkout.php" class="btn btn-success">Thanh Toán</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Checkout Section End -->

<?php
require_once('modules/footer.php');
?>