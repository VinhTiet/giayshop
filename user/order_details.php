<?php
session_start();

require('./db/connect.php');
require('./modules/header.php');

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem chi tiết đơn hàng!'); window.location='loginform.php';</script>";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location='order_status.php';</script>";
    exit;
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user']['id'];

// Kiểm tra đơn hàng có thuộc về người dùng hay không
$sql_order = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$result_order = mysqli_query($conn, $sql_order);

if (mysqli_num_rows($result_order) === 0) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location='order_status.php';</script>";
    exit;
}

$order = mysqli_fetch_assoc($result_order);

// Lấy danh sách sản phẩm trong đơn hàng
$sql_products = "SELECT od.*, p.name AS product_name 
                 FROM order_details od
                 JOIN product p ON od.product_id = p.id
                 WHERE od.order_id = $order_id";
$result_products = mysqli_query($conn, $sql_products);

?>

<div class="container"
    style="max-width: 960px; margin: 0 auto; padding: 30px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 class="text-center" style="font-size: 28px; font-weight: 700; color: #333; margin-bottom: 20px;">Chi Tiết Đơn
        Hàng #<?= htmlspecialchars($order['id']) ?></h2>

    <div class="mb-4">
        <h5
            style="font-size: 22px; font-weight: 600; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; margin-bottom: 15px;">
            Thông Tin Đơn Hàng</h5>
        <ul style="list-style: none; padding-left: 0; font-size: 16px; color: #555;">
            <li style="margin-bottom: 8px;"><strong>Tên khách hàng:</strong>
                <?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></li>
            <li style="margin-bottom: 8px;"><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></li>
            <li style="margin-bottom: 8px;"><strong>Số điện thoại:</strong>
                <?= htmlspecialchars($order['phone_number']) ?></li>
            <li style="margin-bottom: 8px;"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></li>
            <li style="margin-bottom: 8px;"><strong>Ngày đặt hàng:</strong>
                <?= htmlspecialchars($order['created_at']) ?></li>
            <li style="margin-bottom: 8px;"><strong>Trạng thái đơn hàng:</strong>
                <?= htmlspecialchars($order['status']) ?></li>
        </ul>
    </div>

    <h5 style="font-size: 22px; font-weight: 600; color: #333; margin-bottom: 20px;">Danh Sách Sản Phẩm</h5>
    <table style="width: 100%; margin-top: 30px; border-collapse: collapse; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <thead>
            <tr style="background-color: #007bff; color: white; font-weight: bold; text-transform: uppercase;">
                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">#</th>
                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Tên Sản Phẩm</th>
                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Giá</th>
                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Số Lượng</th>
                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Thành Tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0; // Biến tổng tiền
            $count = 1;

            while ($product = mysqli_fetch_assoc($result_products)) {
                $subtotal = $product['num'] * $product['price']; // Thành tiền của từng sản phẩm
                $total += $subtotal; // Cộng dồn vào tổng tiền
                ?>
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 12px; border: 1px solid #ddd; text-align: center;"><?= $count++ ?></td>
                <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                    <?= htmlspecialchars($product['product_name']) ?>
                </td>
                <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                    <?= number_format($product['price'], 0, ',', '.') ?>₫
                </td>
                <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                    <?= htmlspecialchars($product['num']) ?>
                </td>
                <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                    <?= number_format($subtotal, 0, ',', '.') ?>₫
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f8f9fa; text-align: right;">
                <th colspan="4" style="padding: 12px;">Tổng cộng:</th>
                <th style="padding: 12px;"><?= number_format($total, 0, ',', '.') ?>₫</th>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mt-4">
        <a href="order_status.php" class="btn"
            style="background-color: #007bff; color: white; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 4px; text-transform: uppercase;">Quay
            lại</a>
    </div>
</div>

<?php
require('./modules/footer.php');
?>