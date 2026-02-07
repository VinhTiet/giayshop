<?php
require('./modules/header.php');

/* Kết nối DB: dùng đường dẫn tuyệt đối theo thư mục file hiện tại */

// cate.php (hoặc addcate.php)

// ==== TÌM connect.php THEO NHIỀU ỨNG VIÊN, DỪNG KHI THẤY ====
$CANDIDATES = [
  __DIR__ . '/../db/connect.php',            // webbanhang/db/connect.php (thường đúng)
  __DIR__ . '/db/connect.php',               // webbanhang/admincp/db/connect.php
  dirname(__DIR__, 2) . '/db/connect.php',   // webbanhang/../db/connect.php (phòng nest khác)
  dirname(__DIR__) . '/connect.php',         // webbanhang/connect.php (nếu không có thư mục db)
  $_SERVER['DOCUMENT_ROOT'] . '/webbanhang/db/connect.php', // nếu site là /webbanhang
  $_SERVER['DOCUMENT_ROOT'] . '/db/connect.php',            // nếu db ở ngay htdocs/db
];

$__found = false;
foreach ($CANDIDATES as $__p) {
  if (file_exists($__p)) {
    require_once $__p;
    $__found = true;
    break;
  }
}

if (!$__found) {
  // Báo rõ đã thử những đường dẫn nào để bạn nhìn và đặt đúng connect.php
  header('Content-Type: text/plain; charset=utf-8');
  echo "Không tìm thấy db/connect.php. Đã thử các đường dẫn sau:\n";
  foreach ($CANDIDATES as $__p) echo " - " . $__p . "\n";
  exit;
}

// Kiểm tra $conn
if (!isset($conn) || !($conn instanceof mysqli)) {
  die('Kết nối DB ($conn) chưa sẵn sàng. Mở file connect.php và đảm bảo có: $conn = mysqli_connect(...);');
}


/* Lấy danh sách giới tính */
$gender_rs = mysqli_query($conn, "SELECT id, gt FROM gender ORDER BY id");
if ($gender_rs === false) {
  // Hiển thị lỗi để bạn biết nguyên nhân (có thể tắt sau khi xong)
  echo '<div style="color:red;padding:8px 12px;">Lỗi truy vấn gender: ' . htmlspecialchars(mysqli_error($conn)) . '</div>';
}
?>

<div>
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <div class="row">
          <div class="col-lg-12">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Thêm Danh Mục Sản Phẩm</h1>
              </div>

              <form class="user" method="post" action="cate.php">
                <div class="form-group">
                  <label for="name">Tên danh mục</label>
                  <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Tên Danh Mục" required>
                </div>

                <div class="form-group">
                  <label for="slug">Slug</label>
                  <input type="text" class="form-control form-control-user" id="slug" name="slug" placeholder="vd: giay-nam">
                  <small class="text-muted">Để trống sẽ tự tạo từ tên.</small>
                </div>

                <div class="form-group">
                  <label for="gender_id">Giới tính</label>
                  <select class="form-control" id="gender_id" name="gender_id" required>
                    <option value="">Chọn giới tính</option>
                    <?php if ($gender_rs && mysqli_num_rows($gender_rs) > 0): ?>
                      <?php while ($gt = mysqli_fetch_assoc($gender_rs)): ?>
                        <option value="<?= (int)$gt['id'] ?>"><?= htmlspecialchars($gt['gt']) ?></option>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <!-- Không có dữ liệu: vẫn cho submit nhưng sẽ báo lỗi ở cate.php -->
                      <option value="" disabled>(Chưa có dữ liệu giới tính)</option>
                    <?php endif; ?>
                  </select>
                  <?php if ($gender_rs && mysqli_num_rows($gender_rs) == 0): ?>
                    <small class="text-danger">Bảng <code>gender</code> đang trống.</small>
                  <?php endif; ?>
                </div>

                <div class="form-group">
                  <label for="status">Trạng thái</label>
                   <input type="text" class="form-control form-control-user" id="status" name="status" placeholder="Trạng thái" required>
                </div>

                <button class="btn btn-primary">Thêm Mới</button>
              </form>

              <script>
                // Auto tạo slug đơn giản
                function toSlug(str){
                  return str.normalize('NFD').replace(/[\u0300-\u036f]/g,'')
                           .toLowerCase().replace(/đ/g,'d')
                           .replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'');
                }
                const nameInp=document.getElementById('name'), slugInp=document.getElementById('slug');
                nameInp.addEventListener('blur', ()=>{ if(!slugInp.value.trim()) slugInp.value = toSlug(nameInp.value); });
              </script>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require('./modules/footer.php'); ?>
