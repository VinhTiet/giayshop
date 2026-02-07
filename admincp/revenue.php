<?php
require_once('modules/header.php');
// Kiểm tra quyền của người dùng
require __DIR__ . '/../vendor/autoload.php';

if (!isset($_SESSION['admin']['type']) || $_SESSION['admin']['type'] != 'Admin') {
    echo '<h3>Bạn Không Có Quyền Truy Cập Thống Kê Doanh Thu</h3>';
} else {
    require("./db/connect.php");

    $filter_date = $_GET['filter_date'] ?? '';
    $filter_month = $_GET['filter_month'] ?? '';
    $filter_year = $_GET['filter_year'] ?? '';

    $where_clause = '';
    if ($filter_date) {
        $where_clause .= " WHERE DATE(od.created_at) = '$filter_date'";
        $filter_label = "Ngày: " . date('d/m/Y', strtotime($filter_date));
    } elseif ($filter_month) {
        $where_clause .= " WHERE YEAR(od.created_at) = YEAR('$filter_month') AND MONTH(od.created_at) = MONTH('$filter_month')";
        $filter_label = "Tháng: " . date('m/Y', strtotime($filter_month));
    } elseif ($filter_year) {
        $where_clause .= " WHERE YEAR(od.created_at) = '$filter_year'";
        $filter_label = "Năm: " . $filter_year;
    } else {
        $filter_label = "Tất Cả";
    }

$query = "SELECT 
            p.name AS product_name,
            DATE(o.created_at) AS order_date,
            SUM(od.num) AS total_num,
            SUM(od.total) AS daily_revenue
          FROM order_details od
          JOIN orders o ON od.order_id = o.id
          JOIN product p ON od.product_id = p.id
          WHERE o.status = 'Delivered'
          " . ($where_clause ? " AND " . substr($where_clause, 7) : "") . "
          GROUP BY p.name, DATE(o.created_at)
          ORDER BY DATE(o.created_at) DESC, p.name ASC";






           

    $result = mysqli_query($conn, $query);

    $revenue_data = [];
    $total_revenue = 0; // Khởi tạo biến tổng doanh thu

    while ($row = mysqli_fetch_assoc($result)) {
        $revenue_data[] = $row;
        $total_revenue += $row['daily_revenue']; // Tính tổng doanh thu
    }

    mysqli_close($conn);
    ?>
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <h2 class="title_revenue">Thống Kê Doanh Thu <?php echo $filter_label; ?></h2>
                        <form action="" method="GET" class="filter_revenue">
                            <label for="filter_date">Chọn Thời Gian:</label>
                            <input type="date" id="filter_date" name="filter_date">
                            <button type="submit" class="btn btn-primary btn_filter">Lọc</button>
                            <a href="generate_report.php?filter_date=<?php echo $filter_date; ?>&filter_month=<?php echo $filter_month; ?>&filter_year=<?php echo $filter_year; ?>"
                                class="btn btn-danger btn_report">In Hóa Đơn</a>

                        </form>

                        <table class="revenue-table">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Số Lượng</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($revenue_data as $data): ?>
                                <tr>
                                    <td><?php echo $data['order_date']; ?></td>
                                    <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                                    <td><?php echo number_format($data['total_num']); ?></td>
                                    <td><?php echo number_format($data['daily_revenue']); ?> VND</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p class="total-revenue">Tổng Doanh Thu: <span
                                class="number"><?php echo number_format($total_revenue); ?> VND</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
require('./modules/footer.php');
?>