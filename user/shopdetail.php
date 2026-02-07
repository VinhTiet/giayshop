<?php
session_start();
$is_homepage = false;
require("./db/connect.php");


/* ========= BẮT ĐĂNG NHẬP KHI THÊM VÀO GIỎ ========= */
if (isset($_POST['atcbtn']) && !isset($_SESSION['user'])) {
    // Lưu thông báo
    $_SESSION['error_message'] = 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!';

    // Lấy id sản phẩm để quay lại sau khi đăng nhập
    $pid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;

    // Chuyển tới trang đăng nhập, kèm đường dẫn quay lại
    $redirect = "shopdetail.php?id=" . $pid;
    header("Location: loginform.php?redirect=" . urlencode($redirect));
    exit;
}

/* ========= XỬ LÝ THÊM VÀO GIỎ HÀNG (CHỈ CHẠY KHI ĐÃ ĐĂNG NHẬP) ========= */
if (isset($_POST["atcbtn"])) {
    $id   = intval($_POST['pid']);
    $size = trim($_POST['size'] ?? '');
    $qty  = max(1, intval($_POST['qty']));

    
}

/* ==========================
   XỬ LÝ THÊM VÀO GIỎ HÀNG
   ========================== */
if (isset($_POST["atcbtn"])) {
    $id   = intval($_POST['pid']);
    $size = trim($_POST['size'] ?? '');
    $qty  = max(1, intval($_POST['qty']));

    // Kiểm tra sản phẩm
    $stmt = $conn->prepare("SELECT id, name, price, discount, thumbnail FROM product WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $prod = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$prod) {
        $_SESSION['error_message'] = 'Sản phẩm không tồn tại!';
        header("Location: shopdetail.php?id=$id"); exit;
    }

    // Sản phẩm có size?
    $hasSizes = false;
    $chk = $conn->prepare("SELECT COUNT(*) FROM product_size WHERE product_id=? AND size IS NOT NULL AND size<>''");
    $chk->bind_param("i", $id);
    $chk->execute();
    $chk->bind_result($cnt);
    $chk->fetch();
    $chk->close();
    $hasSizes = $cnt > 0;

    if ($hasSizes && $size === '') {
        $_SESSION['error_message'] = 'Vui lòng chọn kích cỡ trước khi thêm vào giỏ!';
        header("Location: shopdetail.php?id=$id"); exit;
    }

    // Tồn kho theo size (nếu có)
    if ($hasSizes) {
        $st2 = $conn->prepare("SELECT stock FROM product_size WHERE product_id=? AND size=?");
        $st2->bind_param("is", $id, $size);
        $st2->execute();
        $stockRow = $st2->get_result()->fetch_assoc();
        $st2->close();

        if (!$stockRow || (int)$stockRow['stock'] < $qty) {
            $_SESSION['error_message'] = 'Không đủ số lượng tồn kho!';
            header("Location: shopdetail.php?id=$id"); exit;
        }
    }

    $effective = ($prod['discount'] > 0 && $prod['discount'] < $prod['price']) ? $prod['discount'] : $prod['price'];

    // Gộp vào giỏ
    $cart = $_SESSION['cart'] ?? [];
    $found = false;
    foreach ($cart as &$it) {
        if ($it['id'] == $id && $it['size'] === $size) {
            // *Không* trừ tồn kho ở đây (trừ khi thanh toán thành công)
            $it['qty'] += $qty;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $cart[] = [
            'id'       => $id,
            'name'     => $prod['name'],
            'size'     => $size,
            'qty'      => $qty,
            'price'    => $prod['price'],
            'discount' => $effective,
            'thumbnail'=> $prod['thumbnail']
        ];
    }
    $_SESSION['cart'] = $cart;

    $_SESSION['success_message'] = 'Đã thêm vào giỏ hàng!';
    header("Location: shopdetail.php?id=$id"); exit;
}

/* ==========================
   LẤY DỮ LIỆU SẢN PHẨM
   ========================== */
$idsp = intval($_GET['id'] ?? 0);
$res = mysqli_query($conn, "SELECT * FROM product WHERE id = $idsp");
$row = mysqli_fetch_assoc($res);
if (!$row) { die("Không tìm thấy sản phẩm."); }

// Lấy size có giá trị
$sql_sizes = "SELECT size, stock 
              FROM product_size 
              WHERE product_id = $idsp 
                AND size IS NOT NULL AND size <> ''
              ORDER BY CASE WHEN size REGEXP '^[0-9]+$' THEN CAST(size AS UNSIGNED) ELSE 1000 END, size";
$result_sizes = mysqli_query($conn, $sql_sizes);
$sizes = mysqli_fetch_all($result_sizes, MYSQLI_ASSOC);

// Ảnh
$thumbs = array_filter(explode(';', $row['thumbnail']));
$mainThumb = !empty($thumbs) ? $thumbs[0] : 'uploads/no-image.png';

require_once('modules/header.php');
?>
<style>
/* ===== Giá bán ===== */
.price-block { display:flex; align-items:baseline; gap:10px; margin:8px 0 14px; }
.price-block .new-price {
  font-size: 28px;
  font-weight: 800;
  line-height: 1;
}
.price-block .old-price {
  font-size: 16px;
  text-decoration: line-through;
  opacity: 0.65;
}

/* ===== Size UI ===== */
.size-option{
  display:inline-block; padding:6px 10px; border:1px solid #ddd;
  margin:0 6px 6px 0; cursor:pointer; border-radius:4px; user-select:none;
}
.size-option.selected{ border-color:#000; font-weight:600; }
.size-option.disabled{ opacity:.4; cursor:not-allowed; }
</style>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <div class="breadcrumb__text">
          <h2><?= htmlspecialchars($row['name']) ?></h2>
          <div class="breadcrumb__option">
            <a href="index.php">Trang chủ</a>
            <a href="shop.php">Sản phẩm</a>
            <span>Chi tiết</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Breadcrumb Section End -->

<?php
if (isset($_SESSION['error_message'])) {
  echo "<script>alert('".addslashes($_SESSION['error_message'])."');</script>";
  unset($_SESSION['error_message']);
}
if (isset($_SESSION['success_message'])) {
  echo "<script>alert('".addslashes($_SESSION['success_message'])."');</script>";
  unset($_SESSION['success_message']);
}
?>

<section class="product-details spad">
  <div class="container">
    <div class="row">
      <!-- Ảnh -->
      <div class="col-lg-6 col-md-6">
        <div class="product__details__pic">
          <div class="product__details__pic__item">
            <img class="product__details__pic__item--large"
                 src="<?= "../admincp/" . htmlspecialchars($mainThumb) ?>"
                 alt="<?= htmlspecialchars($row['name']) ?>">
          </div>
          <div class="product__details__pic__slider owl-carousel">
            <?php foreach ($thumbs as $t): ?>
              <img data-imgbigurl="<?= "../admincp/" . htmlspecialchars($t) ?>"
                   src="<?= "../admincp/" . htmlspecialchars($t) ?>" alt="">
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Thông tin -->
      <div class="col-lg-6 col-md-6">
        <div class="product__details__text">
          <h3><?= htmlspecialchars($row['name']) ?></h3>

          <!-- ===== Khối giá (KM to, giá gốc gạch) ===== -->
          <div class="product__details__price price-block">
            <?php if ($row['discount'] > 0 && $row['discount'] < $row['price']): ?>
              <span class="new-price">
                <?= number_format($row['discount'], 0, '', '.') ?><sup>đ</sup>
              </span>
              <span class="old-price">
                <?= number_format($row['price'], 0, '', '.') ?><sup>đ</sup>
              </span>
            <?php else: ?>
              <span class="new-price">
                <?= number_format($row['price'], 0, '', '.') ?><sup>đ</sup>
              </span>
            <?php endif; ?>
          </div>

          <p><?= nl2br(htmlspecialchars($row['summary'])) ?></p>

          <form method="post" action="">
            <input type="hidden" name="pid" value="<?= $idsp ?>">

            <!-- Số lượng -->
            <div class="product__details__quantity">
              <div class="pro-qty">
                <input type="number" name="qty" value="1" min="1">
              </div>
            </div>

            <!-- Size -->
            <?php if (!empty($sizes)): ?>
              <div class="product__details__size">
                <label for="size">Chọn kích cỡ:</label>
                <div class="sizes">
                  <?php foreach ($sizes as $s): ?>
                    <?php $disabled = ((int)$s['stock'] <= 0) ? 'disabled' : ''; ?>
                    <label class="size-option <?= $disabled ?>" data-size="<?= htmlspecialchars($s['size']) ?>">
                      <input type="radio" name="size" value="<?= htmlspecialchars($s['size']) ?>" <?= $disabled ?>>
                      <?= htmlspecialchars($s['size']) ?>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
              <script>
                document.querySelectorAll('.size-option').forEach(lbl=>{
                  lbl.addEventListener('click', ()=>{
                    if(lbl.classList.contains('disabled')) return;
                    document.querySelectorAll('.size-option').forEach(x=>x.classList.remove('selected'));
                    lbl.classList.add('selected');
                    const rd = lbl.querySelector('input[type="radio"]');
                    if (rd) rd.checked = true;
                  });
                });
              </script>
            <?php endif; ?>

            <button class="primary-btn" name="atcbtn">Thêm Vào Giỏ Hàng</button>
          </form>

          <!-- Tình trạng -->
          <ul class="mt-3">
            <li><b>Tình Trạng:</b>
              <span>
                <?php
                  if (empty($sizes)) {
                    echo "Còn hàng";
                  } else {
                    $total_stock = array_sum(array_map(fn($x)=>(int)$x['stock'], $sizes));
                    echo $total_stock > 0 ? "Còn hàng ($total_stock sản phẩm)" : "Hết hàng";
                  }
                ?>
              </span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Tab -->
      <div class="col-lg-12">
        <div class="product__details__tab mt-4">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tabs-1">Mô tả</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabs-2">Đánh giá</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
              <div class="product__details__tab__desc">
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
              </div>
            </div>
            <div class="tab-pane" id="tabs-2" role="tabpanel">
              <div class="product__details__tab__desc">
                <p>Chưa có đánh giá cho sản phẩm này.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Liên quan -->
      <div class="col-lg-12 mt-5">
        <div class="section-title related__product__title">
          <h2>Sản phẩm liên quan</h2>
        </div>
        <div class="row">
          <?php
          $cate = (int)$row['category_id'];
          $rel = mysqli_query($conn, "SELECT * FROM product WHERE category_id=$cate AND id<>$idsp LIMIT 4");
          while ($p = mysqli_fetch_assoc($rel)):
            $arr = array_filter(explode(';', $p['thumbnail']));
            $thumb = !empty($arr) ? $arr[0] : 'uploads/no-image.png';
            $relPrice = ($p['discount'] > 0 && $p['discount'] < $p['price']) ? $p['discount'] : $p['price'];
          ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
              <div class="product__item">
                <div class="product__item__pic set-bg" data-setbg="<?= "../admincp/" . htmlspecialchars($thumb) ?>"></div>
                <div class="product__item__text">
                  <h6><a href="shopdetail.php?id=<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></a></h6>
                  <h5><?= number_format($relPrice, 0, '', '.') ?><sup>đ</sup></h5>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<?php require_once('modules/footer.php'); ?>
