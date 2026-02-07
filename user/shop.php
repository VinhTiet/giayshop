<?php
session_start();
$is_homepage = false;
require "./db/connect.php";

/* ==== Nhận tham số danh mục (ưu tiên id, fallback slug) ==== */
$category_id = 0;
$category_name = 'Tất Cả Sản Phẩm';

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
  $category_id = (int)$_GET['id'];
} elseif (!empty($_GET['category'])) {
  $slug = $_GET['category'];
  $st = mysqli_prepare($conn, "SELECT id, name FROM category WHERE slug=? LIMIT 1");
  mysqli_stmt_bind_param($st, "s", $slug);
  mysqli_stmt_execute($st);
  $rs = mysqli_stmt_get_result($st);
  if ($r = mysqli_fetch_assoc($rs)) {
    $category_id = (int)$r['id'];
    $category_name = $r['name'];
  }
}

if ($category_id > 0 && $category_name === 'Tất Cả Sản Phẩm') {
  $st = mysqli_prepare($conn, "SELECT name FROM category WHERE id=? LIMIT 1");
  mysqli_stmt_bind_param($st, "i", $category_id);
  mysqli_stmt_execute($st);
  $rs = mysqli_stmt_get_result($st);
  if ($r = mysqli_fetch_assoc($rs)) $category_name = $r['name'];
}

/* ==== Danh sách danh mục cho sidebar ==== */
$cats_sql = "SELECT id, name, slug FROM category ORDER BY name";
$cats_rs  = mysqli_query($conn, $cats_sql);

/* ==== Câu truy vấn sản phẩm theo danh mục ==== */
$base_products_sql =
  "SELECT p.id AS pid, p.name AS pname, p.thumbnail, p.price, c.slug AS cslug
   FROM product p
   JOIN category c ON p.category_id = c.id ";

$where = ($category_id > 0) ? "WHERE c.id = {$category_id} " : "";
$order = "ORDER BY p.id DESC";

/* ==== Phân trang (dùng cùng điều kiện) ==== */
$products_per_page = 6;
$current_page = (isset($_GET['page']) && ctype_digit($_GET['page'])) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

$count_sql = ($category_id > 0)
  ? "SELECT COUNT(*) AS total FROM product WHERE category_id = {$category_id}"
  : "SELECT COUNT(*) AS total FROM product";
$result_count = mysqli_query($conn, $count_sql);
$total_products = (int)mysqli_fetch_assoc($result_count)['total'];
$total_pages = max(1, (int)ceil($total_products / $products_per_page));

$prod_sql_paginated = $base_products_sql . $where . $order .
  " LIMIT {$products_per_page} OFFSET {$offset}";
$prod_rs = mysqli_query($conn, $prod_sql_paginated);

require_once 'modules/header.php';
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <div class="breadcrumb__text">
          <h2>VPACE</h2>
          <div class="breadcrumb__option">
            <a href="index.php">Trang Chủ</a>
            <span>Sản Phẩm</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Section Begin -->
<section class="product spad">
  <div class="container">
    <div class="row">
      <!-- SIDEBAR -->
      <div class="col-lg-3 col-md-5">
        <div class="sidebar">
          <div class="sidebar__item">
            <h4>Danh Mục Sản Phẩm</h4>
            <ul class="category-list">
              <?php if ($cats_rs && mysqli_num_rows($cats_rs) > 0): ?>
                <?php while ($cat = mysqli_fetch_assoc($cats_rs)): ?>
                  <li>
                    <a href="shop.php?id=<?= (int)$cat['id'] ?>">
                      <?= htmlspecialchars($cat['name']) ?>
                    </a>
                  </li>
                <?php endwhile; ?>
              <?php else: ?>
                <li>Chưa có danh mục</li>
              <?php endif; ?>
            </ul>
          </div>

          <!-- Sản phẩm mới -->
          <div class="sidebar__item">
            <div class="latest-product__text">
              <h4>Sản Phẩm Mới</h4>
              <div class="latest-product__slider owl-carousel">
                <div class="latest-prdouct__slider__item">
                  <?php
                  $latest1 = mysqli_query($conn, "SELECT id, name, price, thumbnail FROM product ORDER BY created_at DESC LIMIT 0,3");
                  while ($lp = mysqli_fetch_assoc($latest1)):
                    $thumbs = explode(';', $lp['thumbnail']);
                  ?>
                    <a href="shopdetail.php?id=<?= (int)$lp['id'] ?>" class="latest-product__item">
                      <div class="latest-product__item__pic">
                        <img src="<?= "../admincp/" . htmlspecialchars($thumbs[0] ?? '') ?>" alt="">
                      </div>
                      <div class="latest-product__item__text">
                        <h6><?= htmlspecialchars($lp['name']) ?></h6>
                        <span><?= number_format((int)$lp['price'], 0, '', '.') ?><sup>đ</sup></span>
                      </div>
                    </a>
                  <?php endwhile; ?>
                </div>
                <div class="latest-prdouct__slider__item">
                  <?php
                  $latest2 = mysqli_query($conn, "SELECT id, name, price, thumbnail FROM product ORDER BY created_at DESC LIMIT 3,3");
                  while ($lp = mysqli_fetch_assoc($latest2)):
                    $thumbs = explode(';', $lp['thumbnail']);
                  ?>
                    <a href="shopdetail.php?id=<?= (int)$lp['id'] ?>" class="latest-product__item">
                      <div class="latest-product__item__pic">
                        <img src="<?= "../admincp/" . htmlspecialchars($thumbs[0] ?? '') ?>" alt="">
                      </div>
                      <div class="latest-product__item__text">
                        <h6><?= htmlspecialchars($lp['name']) ?></h6>
                        <span><?= number_format((int)$lp['price'], 0, '', '.') ?><sup>đ</sup></span>
                      </div>
                    </a>
                  <?php endwhile; ?>
                </div>
              </div>
            </div>
          </div>
          <!-- /Sản phẩm mới -->
        </div>
      </div>

      <!-- CONTENT -->
      <div class="col-lg-9 col-md-7">
        <div class="filter__item">
          <div class="section-title product__discount__title">
            <h2><?= htmlspecialchars($category_name) ?></h2>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-5">
              <div class="filter__sort">
                <span>Sắp Xếp Theo</span>
                <select id="sortBy" name="sortBy">
                  <option value="0">Mặc Định</option>
                  <option value="1">Tên sản phẩm</option>
                  <option value="2">Giá sản phẩm</option>
                </select>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
              <div class="filter__found">
                <h6>Có <span><?= $total_products ?></span> Sản Phẩm</h6>
              </div>
            </div>
            <div class="col-lg-4 col-md-3">
              <div class="filter__option">
                <span class="icon_grid-2x2"></span>
                <span class="icon_ul"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <?php if ($prod_rs && mysqli_num_rows($prod_rs) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($prod_rs)): ?>
              <?php $thumbs = explode(';', $row['thumbnail']); ?>
              <div class="col-lg-4 col-md-6 col-sm-6">
                <a href="shopdetail.php?id=<?= (int)$row['pid'] ?>" class="product__item">
                  <div class="product__item__pic set-bg" data-setbg="<?= "../admincp/" . htmlspecialchars($thumbs[0] ?? '') ?>"></div>
                  <div class="product__item__text">
                    <h6><?= htmlspecialchars($row['pname']) ?></h6>
                    <h5><?= number_format((int)$row['price'], 0, '', '.') ?><sup>đ</sup></h5>
                  </div>
                </a>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12">Không có sản phẩm.</div>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="product__pagination">
          <?php
            $base = 'shop.php' . ($category_id > 0 ? ('?id='.$category_id.'&') : '?');
            if ($current_page > 1) {
              echo '<a href="'.$base.'page='.($current_page-1).'"><i class="fa fa-long-arrow-left"></i></a>';
            }
            for ($p = 1; $p <= $total_pages; $p++) {
              $active = $p == $current_page ? ' class="active"' : '';
              echo '<a'.$active.' href="'.$base.'page='.$p.'">'.$p.'</a>';
            }
            if ($current_page < $total_pages) {
              echo '<a href="'.$base.'page='.($current_page+1).'"><i class="fa fa-long-arrow-right"></i></a>';
            }
          ?>
        </div>
        <!-- /Pagination -->
      </div>
    </div>
  </div>
</section>
<!-- Product Section End -->

<?php require_once 'modules/footer.php'; ?>
