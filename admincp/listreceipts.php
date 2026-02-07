<?php
require('./modules/header.php');
require('./db/connect.php');

$q = "
SELECT pr.receipt_id, pr.receipt_date, pr.total_amount, pr.note,
       s.supplier_name,
       a.name AS admin_name
FROM purchase_receipts pr
JOIN suppliers s ON s.supplier_id = pr.supplier_id
LEFT JOIN admin a ON a.id = pr.created_by
ORDER BY pr.receipt_id DESC
";
$rs = mysqli_query($conn, $q);
?>
<a href="printreceipt.php?id=<?= $receipt['receipt_id'] ?>" target="_blank" class="btn btn-primary btn-sm">
  In PDF
</a>

<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Danh Sách Phiếu Nhập</h6>
    <a href="addreceipt.php" class="btn btn-success btn-sm">+ Tạo Phiếu Nhập</a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable">
        <thead>
          <tr>
            <th>#</th><th>Ngày</th><th>Nhà cung cấp</th><th>Người lập</th><th>Tổng tiền</th><th>Ghi chú</th><th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php while($r = mysqli_fetch_assoc($rs)) : ?>
          <tr>
            <td><?= $r['receipt_id'] ?></td>
            <td><?= $r['receipt_date'] ?></td>
            <td><?= htmlspecialchars($r['supplier_name']) ?></td>
            <td><?= htmlspecialchars($r['admin_name'] ?? '—') ?></td>
            <td><?= number_format($r['total_amount'],2) ?></td>
            <td><?= htmlspecialchars($r['note']) ?></td>
            <td>
              <a class="btn btn-info btn-sm" href="viewreceipt.php?id=<?= $r['receipt_id'] ?>">Chi tiết</a>
              <a class="btn btn-primary btn-sm" href="printreceipt.php?id=<?= $r['receipt_id'] ?>" target="_blank">In PDF</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require('./modules/footer.php'); ?>
