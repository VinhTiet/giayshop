<?php
require('./modules/header.php');

?>

<style>
/* CSS cho trạng thái đơn hàng */
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
    background-color: red;
    color: #fff;
    padding: 5px;
    border-radius: 4px;
}

/* CSS cho trạng thái thanh toán */
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
    background-color: lightcoral;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

/* CSS cho trạng thái giao hàng */
.shipping-pending {
    background-color: lightgray;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.shipping-in-transit {
    background-color: skyblue;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.shipping-delivered {
    background-color: greenyellow;
    color: #000;
    padding: 5px;
    border-radius: 4px;
}

.shipping-canceled {
    background-color: orangered;
    color: #fff;
    padding: 5px;
    border-radius: 4px;
}
</style>

<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">

            <h6 class="m-0 font-weight-bold text-primary">Danh Sách Đơn Hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã Đơn Hàng</th>
                            <th>Ngày Đặt</th>
                            <th>Trạng Thái</th>
                            <th>Thanh Toán</th>
                            <th>Ngày Giao Dự Kiến</th>
                            <th>Chức Năng</th>
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
                        while ($row = mysqli_fetch_assoc($result)) {
                            $stt++;

                            // Lấy trạng thái đơn hàng
                            $status_class = match ($row['status']) {
                                'Processing' => 'status-processing',
                                'Confirmed' => 'status-confirmed',
                                'Shipping' => 'status-shipping',
                                'Delivered' => 'status-delivered',
                                'Cancelled' => 'status-cancelled',
                                default => ''
                            };
                            $status_text = $status_mapping[$row['status']] ?? 'Không xác định';

                            // Lấy trạng thái thanh toán
                            $payment_class = match ($row['payment_status']) {
                                'Pending' => 'payment-pending',
                                'Paid' => 'payment-paid',
                                'Failed' => 'payment-failed',
                                default => ''
                            };
                            $payment_text = $payment_mapping[$row['payment_status']] ?? 'Không xác định';

                            // Lấy trạng thái giao hàng
                            $shipping_class = match ($row['shipping_status']) {
                                'Pending' => 'shipping-pending',
                                'In Transit' => 'shipping-in-transit',
                                'Delivered' => 'shipping-delivered',
                                'Canceled' => 'shipping-canceled',
                                default => ''
                            };
                            $shipping_text = $shipping_mapping[$row['shipping_status']] ?? 'Không xác định';
                            ?>
                        <tr>
                            <td><?= $stt ?></td>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td><span class="<?= $status_class ?>"><?= $status_text ?></span></td>
                            <!-- Sử dụng $status_text -->
                            <td><span class="<?= $payment_class ?>"><?= $payment_text ?></span></td>
                            <!-- Sử dụng $payment_text -->
                            <td><?= $row['estimated_delivery'] ?? 'Chưa xác định' ?></td>
                            <td>
                                <a class="btn btn-primary" href="vieworders.php?id=<?= $row['id'] ?>">Xem</a>
                            </td>
                        </tr>

                        <?php
                        }
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require('./modules/footer.php');
?>