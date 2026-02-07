<?php
session_start();

require('./db/connect.php');
require('./modules/header.php');

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem đơn hàng của bạn!'); window.location='loginform.php';</script>";
    exit;
}

$user_id = $_SESSION['user']['id'];

// Lấy danh sách đơn hàng của người dùng
$sql_orders = "
    SELECT o.id, o.created_at, o.status, o.payment_status, 
           SUM(od.num * od.price) AS total
    FROM orders o
    LEFT JOIN order_details od ON o.id = od.order_id
    WHERE o.user_id = $user_id
    GROUP BY o.id, o.created_at, o.status, o.payment_status
    ORDER BY o.created_at DESC
";


$result_orders = mysqli_query($conn, $sql_orders);

?>
<style>
/* Trạng thái đơn hàng */
.status-processing {
    background-color: lightcoral;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.status-confirmed {
    background-color: orange;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.status-shipping {
    background-color: skyblue;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.status-delivered {
    background-color: lightgreen;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.status-cancelled {
    background-color: orangered;
    color: #fff;
    padding: 5px;
    border-radius: 4px;
}

/* Trạng thái thanh toán */
.payment-pending {
    background-color: lightgray;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.payment-paid {
    background-color: lightgreen;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.payment-failed {
    background-color: lightpink;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}
</style>


<div class="container my-5">
    <h2 class="text-center mb-4">Thông Tin Đơn Hàng</h2>
    <?php if (mysqli_num_rows($result_orders) > 0): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày đặt</th>
                <th>Trạng thái đơn hàng</th>
                <th>Tổng tiền</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php
                require("./db/connect.php");

                // Lấy danh sách đơn hàng
                $sql_str = "SELECT * FROM orders ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql_str);
                $stt = 0;
                // Mảng ánh xạ trạng thái sang tiếng Việt
                $status_mapping = [
                    'Processing' => 'Đang xử lý',
                    'Confirmed' => 'Đã xác nhận',
                    'Shipping' => 'Đang vận chuyển',
                    'Delivered' => 'Đã giao hàng',
                    'Cancelled' => 'Đã hủy',
                ];

                $payment_mapping = [
                    'Pending' => 'Chưa thanh toán',
                    'Paid' => 'Đã thanh toán',
                    'Failed' => 'Thanh toán thất bại',
                ];

                $shipping_mapping = [
                    'Pending' => 'Chưa vận chuyển',
                    'In Transit' => 'Đang vận chuyển',
                    'Delivered' => 'Đã giao hàng',
                    'Canceled' => 'Đã hủy vận chuyển',
                ];
                while ($order = mysqli_fetch_assoc($result_orders)):
                    // Gán lớp CSS cho trạng thái đơn hàng
                    $status_class = match ($order['status']) {
                        'Processing' => 'status-processing',
                        'Confirmed' => 'status-confirmed',
                        'Shipping' => 'status-shipping',
                        'Delivered' => 'status-delivered',
                        'Cancelled' => 'status-cancelled',
                        default => ''
                    };
                    $status_text = $status_mapping[$order['status']] ?? 'Không xác định';

                    // Gán lớp CSS cho trạng thái thanh toán
                    $payment_class = match ($order['payment_status']) {
                        'Pending' => 'payment-pending',
                        'Paid' => 'payment-paid',
                        'Failed' => 'payment-failed',
                        default => ''
                    };
                    $payment_text = $payment_mapping[$order['payment_status']] ?? 'Không xác định';
                    ?>
            <tr>
                <td><?= htmlspecialchars($order['id']) ?></td>
                <td><?= htmlspecialchars($order['created_at']) ?></td>
                <td>
                    <span class="<?= $status_class ?>"><?= $status_text ?></span>
                </td>

                <td><?= number_format($order['total'], 0, ',', '.') ?>₫</td>
                <td>
                    <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">Xem Chi Tiết</a>
                </td>
            </tr>
            <?php endwhile; ?>


        </tbody>
    </table>
    <?php else: ?>
    <p class="text-center">Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>

<?php
require('./modules/footer.php');
?>