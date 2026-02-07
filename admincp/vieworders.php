<?php
require('./modules/header.php');
require('./db/connect.php');

/* --- Validate id --- */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location='listorders.php';</script>";
    exit;
}
$id = (int)$_GET['id'];

/* --- Lấy thông tin đơn hàng --- */
$stmtOrder = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmtOrder->bind_param("i", $id);
$stmtOrder->execute();
$orderRes = $stmtOrder->get_result();
if ($orderRes->num_rows === 0) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location='listorders.php';</script>";
    exit;
}
$order = $orderRes->fetch_assoc();

/* --- Cập nhật trạng thái đơn hàng --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status          = $_POST['status'] ?? $order['status'];
    $payment_status  = $_POST['payment_status'] ?? $order['payment_status'];
    $estimated_delivery = trim($_POST['estimated_delivery'] ?? '');
    $estimated_delivery = ($estimated_delivery === '') ? null : $estimated_delivery;

    $sqlUpdate = "UPDATE orders 
                  SET status = ?, payment_status = ?, estimated_delivery = ?, updated_at = CURRENT_TIMESTAMP
                  WHERE id = ?";
    $stmtUpd = $conn->prepare($sqlUpdate);
    $stmtUpd->bind_param("sssi", $status, $payment_status, $estimated_delivery, $id);

    if ($stmtUpd->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location='vieworders.php?id={$id}';</script>";
        exit;
    } else {
        echo "<script>alert('Cập nhật thất bại: ".htmlspecialchars($conn->error)."');</script>";
    }
}

/* --- Lấy danh sách sản phẩm trong đơn, có size --- */
$sqlProducts = "
    SELECT 
        od.*, 
        p.name AS product_name, 
        od.price AS order_price,
        COALESCE(ps.size, od.size) AS size_label
    FROM order_details od
    JOIN product p        ON od.product_id = p.id
    LEFT JOIN product_size ps ON ps.id = od.size_id
    WHERE od.order_id = ?
";
$stmtItems = $conn->prepare($sqlProducts);
$stmtItems->bind_param("i", $id);
$stmtItems->execute();
$result_products = $stmtItems->get_result();

/* --- Danh sách nhân viên (nếu cần gán phụ trách) --- */
$result_employees = $conn->query("SELECT id, name FROM admin ORDER BY name");
?>

<div class="container">
  <div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body">
      <h1 class="h3 text-gray-900 mb-4 text-center">Chi Tiết Đơn Hàng</h1>

      <div class="row">
        <div class="col-lg-6">
          <h5>Thông Tin Khách Hàng</h5>
          <ul>
            <li><strong>Tên khách hàng:</strong> <?= htmlspecialchars($order['firstname'].' '.$order['lastname']) ?></li>
            <li><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></li>
            <li><strong>Điện thoại:</strong> <?= htmlspecialchars($order['phone_number']) ?></li>
            <li><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></li>
            <li><strong>Ghi chú:</strong> <?= $order['note'] ? htmlspecialchars($order['note']) : 'Không có' ?></li>
          </ul>
        </div>
        <div class="col-lg-6">
          <h5>Thông Tin Đơn Hàng</h5>
          <ul>
            <li><strong>Mã đơn hàng:</strong> <?= $order['id'] ?></li>
            <li><strong>Ngày đặt hàng:</strong> <?= htmlspecialchars($order['created_at']) ?></li>
            <li><strong>Trạng thái đơn hàng:</strong>
              <span class="badge badge-info"><?= htmlspecialchars($order['status']) ?></span>
            </li>
            <li><strong>Trạng thái thanh toán:</strong>
              <span class="badge badge-success"><?= htmlspecialchars($order['payment_status']) ?></span>
            </li>
            <li><strong>Ngày giao dự kiến:</strong> <?= $order['estimated_delivery'] ? htmlspecialchars($order['estimated_delivery']) : 'Chưa xác định' ?></li>
          </ul>
        </div>
      </div>

      <hr>

      <h5>Chi Tiết Sản Phẩm</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Tên Sản Phẩm</th>
            <th>Size</th>
            <th>Giá</th>
            <th>Số Lượng</th>
            <th>Tổng</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0; $count = 1;
          while ($product = $result_products->fetch_assoc()):
              $subtotal = ((int)$product['num']) * ((int)$product['order_price']);
              $total += $subtotal;
          ?>
          <tr>
            <td><?= $count++ ?></td>
            <td><?= htmlspecialchars($product['product_name']) ?></td>
            <td><?= htmlspecialchars($product['size_label'] ?: '—') ?></td>
            <td><?= number_format((int)$product['order_price'], 0, ',', '.') ?>₫</td>
            <td><?= (int)$product['num'] ?></td>
            <td><?= number_format($subtotal, 0, ',', '.') ?>₫</td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="5" class="text-right">Tổng cộng:</th>
            <th><?= number_format($total, 0, ',', '.') ?>₫</th>
          </tr>
        </tfoot>
      </table>

      <hr>

      <h5>Cập Nhật Trạng Thái</h5>
      <form method="POST">
        <div class="form-group">
          <label for="status">Trạng thái đơn hàng</label>
          <select name="status" id="status" class="form-control">
            <option value="Processing" <?= $order['status']=='Processing'?'selected':''; ?>>Đang xử lý</option>
            <option value="Confirmed"  <?= $order['status']=='Confirmed'?'selected':''; ?>>Đã xác nhận</option>
            <option value="Shipping"   <?= $order['status']=='Shipping'?'selected':''; ?>>Đang vận chuyển</option>
            <option value="Delivered"  <?= $order['status']=='Delivered'?'selected':''; ?>>Đã giao</option>
            <option value="Cancelled"  <?= $order['status']=='Cancelled'?'selected':''; ?>>Đã hủy</option>
          </select>
        </div>

        <div class="form-group">
          <label for="payment_status">Trạng thái thanh toán</label>
          <select name="payment_status" id="payment_status" class="form-control">
            <option value="Pending" <?= $order['payment_status']=='Pending'?'selected':''; ?>>Chưa thanh toán</option>
            <option value="Paid"    <?= $order['payment_status']=='Paid'?'selected':''; ?>>Đã thanh toán</option>
            <option value="Failed"  <?= $order['payment_status']=='Failed'?'selected':''; ?>>Thanh toán thất bại</option>
          </select>
        </div>

        <div class="form-group">
          <label for="estimated_delivery">Ngày giao dự kiến</label>
          <input type="date" name="estimated_delivery" id="estimated_delivery" class="form-control"
                 value="<?= htmlspecialchars($order['estimated_delivery'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label for="employee_id">Người phụ trách</label>
          <select name="employee_id" id="employee_id" class="form-control">
            <?php while ($employee = $result_employees->fetch_assoc()): ?>
              <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['name']) ?></option>
            <?php endwhile; ?>
          </select>
          <!-- Gợi ý: nếu muốn lưu người phụ trách, tạo cột assigned_admin_id trong orders và update tương tự. -->
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="listorders.php" class="btn btn-secondary">Quay lại</a>
      </form>

    </div>
  </div>
</div>

<?php require('./modules/footer.php'); ?>
