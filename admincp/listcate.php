<?php
require('./modules/header.php');
?>

<div>
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Danh Mục Sản Phẩm</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Tên Danh Mục Sản Phẩm</th>
              <th>Slug</th>
              <th>Trạng Thái</th>
              <th>Giới Tính</th> <!-- thêm cột -->
              <th>Chức Năng</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Nếu file này nằm trong admincp/ và connect.php ở webbanhang/db/connect.php,
            // dùng dòng dưới an toàn hơn:
            // require_once dirname(__DIR__) . '/db/connect.php';
            require("./db/connect.php");

            // JOIN bảng gender để lấy tên giới tính
            $sql_str = "
              SELECT c.id, c.name, c.slug, c.status, c.gender_id, g.gt AS gender_name
              FROM category c
              LEFT JOIN gender g ON c.gender_id = g.id
              ORDER BY c.id
            ";
            $result = mysqli_query($conn, $sql_str);

            while ($row = mysqli_fetch_assoc($result)) {
              $genderLabel = $row['gender_name'] ?: '-';
              ?>
              <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['slug']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                  <?php if ($genderLabel === 'Nam'): ?>
                    <span class="badge badge-primary">Nam</span>
                  <?php elseif ($genderLabel === 'Nữ'): ?>
                    <span class="badge badge-danger">Nữ</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a class="btn btn-primary btn-sm" href="editcate.php?id=<?= (int)$row['id'] ?>">Chỉnh Sửa</a>
                  <a class="btn btn-danger btn-sm"
                     href="deletecate.php?id=<?= (int)$row['id'] ?>"
                     onclick="return confirm('Bạn chắc chắn muốn xóa mục này?');">Xóa</a>
                </td>
              </tr>
              <?php
              if (isset($_SESSION['message'])) {
                echo "<tr><td colspan='5'><h3 class='message'>".htmlspecialchars($_SESSION['message'])."</h3></td></tr>";
                unset($_SESSION['message']);
              }
            }
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
