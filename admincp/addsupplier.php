<?php
require('./modules/header.php');
require('./db/connect.php');

if (isset($_POST['submit'])) {
  $name    = trim($_POST['supplier_name']);
  $address = trim($_POST['supplier_address']);
  $phone   = trim($_POST['supplier_phone']);
  $email   = trim($_POST['supplier_email']);
  $note    = trim($_POST['note']);

  $sql = "INSERT INTO suppliers (supplier_name, supplier_address, supplier_phone, supplier_email, note)
          VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sssss", $name, $address, $phone, $email, $note);

  if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Thêm nhà cung cấp thành công!'); window.location='listsupplier.php';</script>";
  } else {
    echo "<div class='alert alert-danger'>Lỗi khi thêm: " . mysqli_error($conn) . "</div>";
  }
}
?>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Thêm Nhà Cung Cấp</h6>
  </div>

  <div class="card-body">
    <form action="" method="POST">
      <div class="form-group">
        <label>Tên Nhà Cung Cấp</label>
        <input type="text" name="supplier_name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Địa Chỉ</label>
        <input type="text" name="supplier_address" class="form-control">
      </div>
      <div class="form-group">
        <label>Số Điện Thoại</label>
        <input type="text" name="supplier_phone" class="form-control">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="supplier_email" class="form-control">
      </div>
      <div class="form-group">
        <label>Ghi Chú</label>
        <textarea name="note" class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Thêm Nhà Cung Cấp</button>
      <a href="listsupplier.php" class="btn btn-secondary">Hủy</a>
    </form>
  </div>
</div>

<?php require('./modules/footer.php'); ?>
