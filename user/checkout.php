<?php
session_start();
$is_homepage = false;
require("./db/connect.php");
$loggedIn = isset($_SESSION['user']);

$cart = $_SESSION['cart'] ?? [];

/* BẮT BUỘC đăng nhập cả phía server */
if (isset($_POST["btnorder"]) && !$loggedIn) {
    header("Location: login.php"); // hoặc loginform.php
    exit;
}

/* Helper: lấy tên size từ id */
function get_size_label(mysqli $conn, $size_id) {
    if (!$size_id) return null;
    $st = $conn->prepare("SELECT size FROM product_size WHERE id = ?");
    $st->bind_param("i", $size_id);
    $st->execute();
    return $st->get_result()->fetch_column();
}

/* Helper: tra size_id theo (product_id, size) */
function get_size_id_by_text(mysqli $conn, $product_id, $size_text) {
    if (!$product_id || $size_text === null || $size_text === '') return null;
    $st = $conn->prepare("SELECT id FROM product_size WHERE product_id = ? AND size = ?");
    $st->bind_param("is", $product_id, $size_text);
    $st->execute();
    $id = $st->get_result()->fetch_column();
    return $id ?: null;
}

if (isset($_POST["btnorder"])) {
    $firstname = trim($_POST["firstname"]);
    $lastname  = trim($_POST["lastname"]);
    $address   = trim($_POST["address"]);
    $phone     = trim($_POST["phone_number"]);
    $email     = trim($_POST["email"]);
    $note      = trim($_POST["note"]);

    $user_id = $loggedIn ? $_SESSION['user']['id'] : null;

    if (empty($cart)) {
        echo "<script>alert('Giỏ hàng trống.'); window.location='cart.php';</script>";
        exit;
    }

    mysqli_begin_transaction($conn);
    try {
        /* Insert orders */
        $sqlOrder = "INSERT INTO orders
                    (user_id, firstname, lastname, email, phone_number, address, note,
                     created_at, updated_at, status, payment_status, shipping_status, estimated_delivery)
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 'Processing', 'Pending', 'Pending', 'Chưa xác định')";
        $st = $conn->prepare($sqlOrder);
        /* NULL được bind bình thường trong mysqli */
        $st->bind_param(
            "issssss",
            $user_id, $firstname, $lastname, $email, $phone, $address, $note
        );
        if (!$st->execute()) throw new Exception($conn->error);
        $last_order_id = $conn->insert_id;

        /* Insert order_details (có size_id/size) */
        $sqlOD = "INSERT INTO order_details
                  (order_id, product_id, size_id, size, price, num, total, created_at, updated_at)
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $sti = $conn->prepare($sqlOD);

        foreach ($cart as $item) {
            $product_id = (int)$item['id'];
            $qty        = (int)$item['qty'];
            /* Ở site của bạn $item['discount'] đang là “giá bán thực tế” */
            $unit_price = (int)$item['discount'];

            /* Lấy size từ session */
            $size_id   = isset($item['size_id']) ? (int)$item['size_id'] : null;
            $size_text = isset($item['size']) ? (string)$item['size'] : null;

            /* Nếu chưa có size_id mà có size text -> dò size_id */
            if (!$size_id && $size_text) {
                $size_id = get_size_id_by_text($conn, $product_id, $size_text);
            }
            /* Nếu vẫn chưa có size_text mà có size_id -> lấy label để lưu dự phòng */
            if ($size_id && ($size_text === null || $size_text === '')) {
                $size_text = get_size_label($conn, $size_id);
            }

            $line_total = $qty * $unit_price;

            /* bind (order_id, product_id, size_id, size, price, num, total) */
            $sti->bind_param(
                "iiisiii",
                $last_order_id, $product_id, $size_id, $size_text, $unit_price, $qty, $line_total
            );
            if (!$sti->execute()) throw new Exception($conn->error);

            /* (Tùy chọn) Trừ tồn theo size khi đặt hàng
               Nên chuyển sang lúc “Confirmed/Delivered” để tránh giữ hàng ảo. */
            // if ($size_id) {
            //   $upd = $conn->prepare("UPDATE product_size SET stock = GREATEST(stock - ?, 0) WHERE id = ?");
            //   $upd->bind_param("ii", $qty, $size_id);
            //   $upd->execute();
            // }
        }

        mysqli_commit($conn);
        unset($_SESSION["cart"]);
        header("Location: thankyou.php");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Có lỗi khi đặt hàng: ".htmlspecialchars($e->getMessage())."');</script>";
    }
}

require_once('modules/header.php');
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Thanh Toán</h2>
                    <div class="breadcrumb__option">
                        <a href="index.php">Trang Chủ</a>
                        <span>Thanh Toán</span>
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
            <h4>Thông Tin Khách Hàng</h4>
            <form method="post">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Họ & Tên Đệm<span>*</span></p>
                                    <input type="text" name="firstname" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Tên<span>*</span></p>
                                    <input type="text" name="lastname" required>
                                </div>
                            </div>
                        </div>
                        <div class="checkout__input">
                            <p>Địa Chỉ<span>*</span></p>
                            <input type="text" class="checkout__input__add" name="address" required>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Số Điện Thoại<span>*</span></p>
                                    <input type="text" name="phone_number" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Email<span>*</span></p>
                                    <input type="email" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="checkout__input">
                            <p>Ghi Chú</p>
                            <input type="text" placeholder="Ghi chú về đơn hàng của bạn..." name="note">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Đơn Hàng Của Bạn</h4>
                            <div class="checkout__order__products">Sản Phẩm<span>Số Tiền</span></div>
                            <ul>
                                <?php
                                $total = 0;
                                foreach ($cart as $item):
                                    $line = $item['qty'] * $item['discount'];
                                    $total += $line;
                                ?>
                                <li>
                                  <?= htmlspecialchars($item['name']) ?>
                                  <?php if (!empty($item['size'])): ?>
                                    <small>(Size: <?= htmlspecialchars($item['size']) ?>)</small>
                                  <?php endif; ?>
                                  <span><?= number_format($item['discount'], 0, '', '.') ?><sup>đ</sup></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="checkout__order__total">
                                Tổng Tiền:
                                <span><?= number_format($total, 0, '', '.') ?><sup>đ</sup></span>
                            </div>

                            <button type="submit" class="site-btn btn-cart" name="btnorder"
                                <?= $loggedIn ? '' : 'disabled'; ?>>Đặt Hàng</button>
                            <div id="login-message" style="color:red; margin-top:10px; <?= $loggedIn ? 'display:none;' : '';?>">
                                Bạn cần đăng nhập để thực hiện thanh toán!
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Checkout Section End -->

<?php require_once('modules/footer.php'); ?>
