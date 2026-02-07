<?php
require('./modules/header.php');
require('./db/connect.php');

// Lấy dữ liệu nhà cung cấp
$sql = "SELECT * FROM suppliers ORDER BY supplier_id DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Danh Sách Nhà Cung Cấp</h6>
    <a href="addsupplier.php" class="btn btn-success btn-sm">+ Thêm Nhà Cung Cấp</a>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên Nhà Cung Cấp</th>
            <th>Địa Chỉ</th>
            <th>Số Điện Thoại</th>
            <th>Email</th>
            <th>Ghi Chú</th>
            <th>Hành Động</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
              <td><?= $row['supplier_id'] ?></td>
              <td><?= htmlspecialchars($row['supplier_name']) ?></td>
              <td><?= htmlspecialchars($row['supplier_address']) ?></td>
              <td><?= htmlspecialchars($row['supplier_phone']) ?></td>
              <td><?= htmlspecialchars($row['supplier_email']) ?></td>
              <td><?= htmlspecialchars($row['note']) ?></td>
              <td>
                <a href="editsupplier.php?id=<?= $row['supplier_id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                <a href="deletesupplier.php?id=<?= $row['supplier_id'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Xác nhận xóa nhà cung cấp này?')">Xóa</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require('./modules/footer.php'); ?>
