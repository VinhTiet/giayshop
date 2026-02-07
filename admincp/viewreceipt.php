<?php
// admincp/viewreceipt.php
require('./modules/header.php');
require('./db/connect.php');

$rid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($rid <= 0) {
  echo "<div class='alert alert-danger'>Không tìm thấy phiếu nhập.</div>";
  require('./modules/footer.php'); exit;
}

/* --- Header phiếu --- */
$header_sql = "
SELECT pr.*, 
       s.supplier_name, s.supplier_phone, s.supplier_email, s.supplier_address,
       a.name AS admin_name
FROM purchase_receipts pr
JOIN suppliers s ON s.supplier_id = pr.supplier_id
LEFT JOIN admin a ON a.id = pr.created_by
WHERE pr.receipt_id = ?
";
$st_hdr = $conn->prepare($header_sql);
$st_hdr->bind_param("i", $rid);
$st_hdr->execute();
$receipt = $st_hdr->get_result()->fetch_assoc();
$st_hdr->close();

if (!$receipt) {
  echo "<div class='alert alert-danger'>Không tìm thấy phiếu nhập.</div>";
  require('./modules/footer.php'); exit;
}

/* --- Items + Size --- */
$items_sql = "
SELECT 
  i.product_id,
  p.name AS product_name,
  ps.size AS size_label,        -- cột size
  i.quantity,
  i.unit_price,
  (i.quantity * i.unit_price) AS subtotal
FROM purchase_receipt_items i
JOIN product p      ON p.id = i.product_id
LEFT JOIN product_size ps ON ps.id = i.size_id
WHERE i.receipt_id = ?
ORDER BY p.name ASC, ps.size ASC
";
$st_it = $conn->prepare($items_sql);
$st_it->bind_param("i", $rid);
$st_it->execute();
$items_rs = $st_it->get_result();
?>

<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Phiếu Nhập #<?= htmlspecialchars($receipt['receipt_id']) ?></h6>
    <div>
      <a href="printreceipt.php?id=<?= (int)$receipt['receipt_id'] ?>" target="_blank" class="btn btn-primary btn-sm">In PDF</a>
      <a href="listreceipts.php" class="btn btn-secondary btn-sm">Quay lại</a>
    </div>
  </div>

  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6">
        <h5>Nhà cung cấp</h5>
        <p class="mb-1"><strong><?= htmlspecialchars($receipt['supplier_name']) ?></strong></p>
        <p class="mb-1"><?= htmlspecialchars($receipt['supplier_address']) ?></p>
        <p class="mb-1">
          Điện thoại: <?= htmlspecialchars($receipt['supplier_phone']) ?> 
          <?php if (!empty($receipt['supplier_email'])): ?>
            | Email: <?= htmlspecialchars($receipt['supplier_email']) ?>
          <?php endif; ?>
        </p>
      </div>
      <div class="col-md-6">
        <h5>Thông tin phiếu</h5>
        <p class="mb-1">Ngày nhập: <?= htmlspecialchars($receipt['receipt_date']) ?></p>
        <p class="mb-1">Người lập: <?= htmlspecialchars($receipt['admin_name'] ?? '—') ?></p>
        <p class="mb-1">Ghi chú: <?= htmlspecialchars($receipt['note']) ?></p>
        <p class="mb-1"><strong>Tổng tiền: <?= number_format((float)$receipt['total_amount'], 0, ',', '.') ?> đ</strong></p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th style="width: 90px;">Mã SP</th>
            <th>Tên sản phẩm</th>
            <th style="width: 90px;">Size</th>
            <th class="text-right" style="width: 120px;">Số lượng</th>
            <th class="text-right" style="width: 140px;">Đơn giá</th>
            <th class="text-right" style="width: 160px;">Thành tiền</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sum = 0;
          while ($row = $items_rs->fetch_assoc()):
            $sum += (float)$row['subtotal'];
        ?>
          <tr>
            <td><?= (int)$row['product_id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['size_label'] ?? '—') ?></td>
            <td class="text-right"><?= (int)$row['quantity'] ?></td>
            <td class="text-right"><?= number_format((float)$row['unit_price'], 0, ',', '.') ?> đ</td>
            <td class="text-right"><?= number_format((float)$row['subtotal'], 0, ',', '.') ?> đ</td>
          </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="5" class="text-right">Tổng cộng:</th>
            <th class="text-right"><?= number_format((float)$sum, 0, ',', '.') ?> đ</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<?php
$st_it->close();
require('./modules/footer.php');
