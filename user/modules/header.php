<?php
//session_start();


$is_homepage = $is_homepage ?? false;

// Kết nối DB
require("./db/connect.php");


function getCatsByGender(mysqli $conn, int $gid): array {
    // Nếu bảng category chắc chắn có cột gender_id
    $sql = "SELECT id, name, slug
            FROM category
            WHERE gender_id = ?
            ORDER BY name";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gid);
    $stmt->execute();
    $res = $stmt->get_result();

    $out = [];
    while ($r = $res->fetch_assoc()) {
        $out[] = $r;
    }
    $stmt->close();
    return $out;
}

$cats_men   = getCatsByGender($conn, 1); // 1 = Nam
$cats_women = getCatsByGender($conn, 2); // 2 = Nữ
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="VPACE Store">
    <meta name="keywords" content="VPACE, giày, sneakers, VPACE Store">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VPACE</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="./assets/css/style.css?v=1.3" type="text/css">

    <style>

      .gender-menu{display:flex;gap:30px;align-items:center;justify-content:center;margin-bottom:0}
      .gender-item{position:relative}
      .gender-btn{
        font-size:18px;font-weight:700;color:#222;text-decoration:none;display:inline-flex;align-items:center;line-height:1;
      }
      .gender-btn::after{content:"▾";font-size:10px;margin-left:6px;opacity:.7}
      .gender-item:hover .gender-btn{color:#FF6900}

      .gender-dropdown{
        position:absolute; left:0; top:100%;
  transform: translateY(8px);       
  min-width: 420px;
  max-width: min(560px, 92vw);       
  padding: 14px 18px;                  
  background:#fff; border-radius:8px;
  box-shadow:0 12px 28px rgba(0,0,0,.12);
  opacity:0; visibility:hidden; transition:opacity .2s, transform .2s, visibility .2s;
  z-index: 99999; pointer-events:none;
      }
     .gender-item:hover .gender-dropdown{
  opacity:1; visibility:visible; transform: translateY(0);
  pointer-events:auto;
}
      .gender-dropdown a{
  display:block;
  padding:6px 0;       
  color:#222 !important;
  text-decoration:none;
  font-size:16px;
  line-height:1.4;
}
      .gender-dropdown a:hover{ color:#FF6900 !important; }

/* đảm bảo không bị cắt */
.hero, .hero-normal { overflow: visible !important; }
.gender-dropdown.gender-grid{
  display:grid;
  grid-template-columns: repeat(2, minmax(160px, 1fr));
  column-gap: 28px;     
  row-gap: 6px;        
}
 
      .container-header{padding-top:10px;padding-bottom:10px}
      .hero__search__form-wrapper{display:flex;gap:20px;align-items:center;justify-content:space-between}
      .cart-icon-container{position:relative}
      .cart-count{position:absolute;top:-8px;right:-10px;background:#ff6600;color:#fff;border-radius:10px;padding:0 6px;font-size:12px}
    </style>
</head>
<body>

<!-- Preloader -->
<div id="preloder"><div class="loader"></div></div>

<!-- Header Section Begin -->
<header class="header">
  <div class="header__top">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="header__top__left"><a>Shop giày VPACE chính hãng</a></div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="header__top__right">
            <!-- <div class="header__top__right__social">
              <a>Khuyến Mãi</a>
              <a>Blog</a>
              <a href="#"><i class="fa fa-facebook"></i></a>
              <a href="#"><i class="fa fa-twitter"></i></a>
              <a href="#"><i class="fa fa-linkedin"></i></a>
              <a href="#"><i class="fa fa-pinterest-p"></i></a>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container container-header">
    <div class="row">
      <div class="col-lg-2">
        <div class="header__logo">
          <a href="index.php"><img src="./assets/img/logocam.png" alt=""></a>
        </div>
      </div>

      <div class="col-lg-8">
        <nav class="header__menu">
          <ul>
            <li><a href="index.php">Trang Chủ</a></li>
            <li><a href="shop.php">Sản Phẩm</a></li>
            <li><a href="blog.php">Tin Tức</a></li>
            <li><a href="feedback.php">Liên Hệ</a></li>
            <li><a href="checkout.php">Thanh Toán</a></li>
            <li><a href="cart.php">Giỏ Hàng</a></li>
          </ul>
        </nav>
      </div>

      <div class="col-lg-2">
        <div class="header__cart">
          <div class="header__top__right__auth">
            <?php if (isset($_SESSION['user'])): ?>
              <div class="dropdown">
                <button class="dropdown-toggle" id="user-dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-user"></i>
                  <span class="user-name">
                    <?= isset($_SESSION['user']['name']) ? htmlspecialchars($_SESSION['user']['name']) : 'User'; ?>
                  </span>
                </button>
                <div class="dropdown-menu" aria-labelledby="user-dropdown">
                  <a class="dropdown-item" href="order_status.php">Thông Tin Đơn Hàng</a>
                  <a class="dropdown-item logout-button" href="logout.php">Đăng Xuất</a>
                </div>
              </div>
              <script>
                $(function(){
                  $('#user-dropdown').on('click', function(e){ e.stopPropagation(); $(this).closest('.dropdown').toggleClass('show') });
                  $(document).on('click', function(e){ if(!$(e.target).closest('.dropdown').length){ $('.dropdown').removeClass('show'); }});
                  $('.logout-button').on('click', function(){ return confirm('Bạn chắc chắn muốn đăng xuất?'); });
                });
              </script>
            <?php else: ?>
              <div class="auth-links"><a href="loginform.php" class="login-button">Đăng Nhập</a></div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</header>
<!-- Header Section End -->

<!-- Hero Section Begin -->
<?php echo $is_homepage ? '<section class="hero">' : '<section class="hero hero-normal">'; ?>
  <div class="container">
    <div class="row">
      <div class="col-lg-9">
        <div class="hero__search__form-wrapper">
          <div class="hero__search__form">
            <form action="search.php" method="get">

              <!-- ====== Nam / Nữ cạnh ô tìm kiếm (dropdown từ DB) ====== -->
              <div class="gender-menu">
                <!-- NAM -->
                <div class="gender-item">
                  <a href="#" class="gender-btn">Nam</a>
                  <?php if(count($cats_men)): ?>
                    <?php
                      $half = (int)ceil(count($cats_men)/2);
                      $men_col1 = array_slice($cats_men, 0, $half);
                      $men_col2 = array_slice($cats_men, $half);
                    ?>
                    <div class="gender-dropdown">
                      <div>
                        <?php foreach($men_col1 as $c): ?>
                          <a href="shop.php?id=<?= (int)$c['id'] ?>">
                            <?= htmlspecialchars($c['name']) ?>
                          </a>
                        <?php endforeach; ?>
                      </div>
                      <div>
                        <?php foreach($men_col2 as $c): ?>
                          <a href="shop.php?id=<?= (int)$c['id'] ?>">
                            <?= htmlspecialchars($c['name']) ?>
                          </a>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>

                <!-- NỮ -->
                <div class="gender-item">
                  <a href="#" class="gender-btn">Nữ</a>
                  <?php if(count($cats_women)): ?>
                    <?php
                      $half2 = (int)ceil(count($cats_women)/2);
                      $w_col1 = array_slice($cats_women, 0, $half2);
                      $w_col2 = array_slice($cats_women, $half2);
                    ?>
                    <div class="gender-dropdown">
                      <div>
                        <?php foreach($w_col1 as $c): ?>
                         <!-- trước: category.php?slug=... hoặc shop.php?category=... -->
<a href="shop.php?id=<?= (int)$c['id'] ?>">
    <?= htmlspecialchars($c['name']) ?>
</a>

                        <?php endforeach; ?>
                      </div>
                      <div>
                        <?php foreach($w_col2 as $c): ?>
                          <!-- trước: category.php?slug=... hoặc shop.php?category=... -->
<a href="shop.php?id=<?= (int)$c['id'] ?>">
    <?= htmlspecialchars($c['name']) ?>
</a>

                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <!-- ====== /Nam Nữ ====== -->

              <input type="text" name="keyword" placeholder="Nhập Sản Phẩm...">
              <button type="submit" class="site-btn">Tìm Kiếm</button>
            </form>
          </div>

          <!-- Cart mini -->
          <div class="hero__cart">
            <ul>
              <li>
                <a href="cart.php">
                  <div class="cart-icon-container">
                    <i class="fa fa-shopping-bag"></i>
                    <span class="cart-count">
                      <?php
                        $cart = $_SESSION['cart'] ?? [];
                        $count = 0; $total = 0;
                        foreach ($cart as $item){
                          $qty = (int)($item['qty'] ?? 0);
                          $count += $qty;
                          $price = (int)($item['discount'] ?? 0); 
                          $total += $qty * $price;
                        }
                        echo (int)$count;
                      ?>
                    </span>
                  </div>
                </a>
              </li>
            </ul>
            <div class="hero__cart__price">Tổng Tiền:
              <span><?= number_format($total, 0, '', '.') . ' VND' ?></span>
            </div>
          </div>
          <!-- /Cart mini -->
        </div>
      </div>

      <div class="col-lg-3">
        <div class="contact-info">
          <span><i class="fa fa-clock-o"></i> 07:00 - 18:00</span>
          <span><i class="fa fa-phone"></i> 0123.456.789</span>
        </div>
      </div>
    </div>
  </div>

  <?php if ($is_homepage): ?>
    <div class="hero__item set-bg" data-setbg="./assets/img/bannercuoi3.png"></div>
  <?php endif; ?>
</section>

<div class="contact-icon">
  <a href="feedback.php" class="contact-link">
    <img src="./assets/img/zalo.jpg" alt="Contact Icon">
  </a>
</div>


</body>
</html>
