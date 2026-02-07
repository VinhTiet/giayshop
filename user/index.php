<?php
session_start();
$is_homepage = true;
require("./db/connect.php");
// Số lượng sản phẩm hiển thị trên mỗi trang
$limit = 5;


// Trang hiện tại
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy id danh mục từ URL
$category_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Lấy tên danh mục
$category_name = "Sản Phẩm Nổi Bật";
if ($category_id > 0) {
    $sql_category_name = "SELECT name FROM category WHERE id = $category_id";
    $category_result = mysqli_query($conn, $sql_category_name);
    if ($category_row = mysqli_fetch_assoc($category_result)) {
        $category_name = $category_row['name'];
    }
}
// Truy vấn danh mục để hiển thị trong bộ lọc
$sql_str = "SELECT * FROM category ORDER BY name";
$result = mysqli_query($conn, $sql_str);

require_once('modules/header.php');
?>

 
<!-- Categories Section Begin -->
<!-- <section class="categories">
        <div class="container">
            <div class="row">
                <div class="section-title">
                    <h2>Danh Mục Nổi Bật</h2>
                </div>
                <div class="categories__slider owl-carousel">
                    <?php
                    $sql_str = "select * from category order by id";
                    $result = mysqli_query($conn, $sql_str);
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="./assets/img/categories/cat-1.jpg">
                            <h5><a href="#"><?= $row['name'] ?></a></h5>
                        </div>
                    </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </section> -->
<!-- Categories Section End -->


<!-- Featured Section Begin -->


<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="product__discount">
                    <div class="section-title product__discount__title">
                        <h2>Giảm Sốc</h2>
                    </div>
                    <div class="row">
                        <div class="product__discount__slider owl-carousel">
                            <?php
                            // Truy vấn để lấy 4 sản phẩm giảm giá nhiều nhất
                            $sql_str = "SELECT 
        product.id AS pid, 
        product.name AS pname, 
        category.name AS cname, 
        ROUND((price - discount) / price * 100) AS dc, 
        thumbnail, 
        price, 
        discount 
    FROM product 
    JOIN category ON product.category_id = category.id 
    ORDER BY dc DESC 
    LIMIT 4";

                            $result = mysqli_query($conn, $sql_str);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $thumbnail_arr = explode(';', $row['thumbnail']);
                                ?>
                                <div class="item">
                                    <div class="product__discount__item">
                                        <div class="product__discount__item__pic set-bg"
                                            data-setbg="<?= "../admincp/" . htmlspecialchars($thumbnail_arr[0]) ?>">
                                            <div class="product__discount__percent">
                                                <?= $row['dc'] ?>%
                                            </div>
                                        </div>
                                        <div class="product__discount__item__text">
                                            <h5>
                                                <a href="shopdetail.php?id=<?= htmlspecialchars($row['pid']) ?>">
                                                    <?= htmlspecialchars($row['pname']) ?>
                                                </a>
                                            </h5>
                                            <div class="product__item__price">
                                                <?= number_format($row['discount'], 0, '', '.') ?><sup>đ</sup>
                                                <span><?= number_format($row['price'], 0, '', '.') ?><sup>đ</sup></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>




                    </div>
                </div>
                <!-- Banners Section Begin -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="banner">
                            <a href="#"><img src="./assets/img/bannermoi.png" alt="Banner 1"></a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="banner">
                            <a href="#"><img src="./assets/img/banner.png" alt="Banner 2"></a>
                        </div>
                    </div>
                </div>
                <div class="section-title">
                    <h2><?= htmlspecialchars($category_name) ?></h2>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            <?php
            // Truy vấn sản phẩm dựa trên danh mục đã chọn
            // if ($category_id > 0) {
            //     $sql_str = "SELECT product.id AS pid, product.name AS pname, thumbnail, price, discount, category.slug AS cslug 
            //                 FROM product 
            //                 JOIN category ON product.category_id = category.id 
            //                 WHERE category.id = $category_id 
            //                 LIMIT $limit OFFSET $offset";
            // } else {
            //     $sql_str = "SELECT product.id AS pid, product.name AS pname, thumbnail, price, discount, category.slug AS cslug 
            //                 FROM product 
            //                 JOIN category ON product.category_id = category.id 
            //                 LIMIT $limit OFFSET $offset";
            // }

$gender = isset($_GET['gender']) ? $_GET['gender'] : '';
if ($category_id > 0) {
    // ...cũ...
} elseif ($gender == 'nam') {
    $sql_str = "SELECT product.id AS pid, product.name AS pname, thumbnail, price, discount, category.slug AS cslug 
                FROM product 
                JOIN category ON product.category_id = category.id 
                WHERE product.gender = 'nam'
                LIMIT $limit OFFSET $offset";
} elseif ($gender == 'nu') {
    $sql_str = "SELECT product.id AS pid, product.name AS pname, thumbnail, price, discount, category.slug AS cslug 
                FROM product 
                JOIN category ON product.category_id = category.id 
                WHERE product.gender = 'nu'
                LIMIT $limit OFFSET $offset";
} else {
    $sql_str = "SELECT product.id AS pid, product.name AS pname, thumbnail, price, discount, category.slug AS cslug 
                FROM product 
                JOIN category ON product.category_id = category.id 
                LIMIT $limit OFFSET $offset";
}

            $result = mysqli_query($conn, $sql_str);
            while ($row = mysqli_fetch_assoc($result)) {
                $thumbnail_arr = explode(';', $row['thumbnail']);
                ?>
                <div class="col-lg-2-4 col-md-3 col-sm-6 mix <?= htmlspecialchars($row['cslug']) ?>">
                    <a href="shopdetail.php?id=<?= htmlspecialchars($row['pid']) ?>"
                        style="text-decoration: none; color: inherit;">
                        <div class="featured__item">
                            <div class="featured__item__pic set-bg"
                                data-setbg="<?= "../admincp/" . htmlspecialchars($thumbnail_arr[0]) ?>">
                            </div>
                            <div class="featured__item__text">
                                <h6>
                                    <?= htmlspecialchars($row['pname']) ?>
                                </h6>
                                <?php if (!empty($row['discount']) && $row['discount'] < $row['price']): ?>
                                    <h5>
                                        <?= number_format($row['discount'], 0, '', '.') ?><sup>đ</sup>
                                    </h5>
                                    <h6 style="text-decoration: line-through; color: gray;">
                                        <?= number_format($row['price'], 0, '', '.') ?><sup>đ</sup>
                                    </h6>
                                <?php else: ?>
                                    <h5><?= number_format($row['price'], 0, '', '.') ?><sup>đ</sup></h5>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>

            <?php } ?>

        </div>

        <!-- Banners Section End -->

        <div class="row">
            <div class="col-lg-12">
                <div class="product__pagination">
                    <?php
                    // Đếm tổng số sản phẩm
                    if ($category_id > 0) {
                        $count_sql = "SELECT COUNT(*) as total FROM product WHERE category_id = $category_id";
                    } else {
                        $count_sql = "SELECT COUNT(*) as total FROM product";
                    }

                    $count_result = mysqli_query($conn, $count_sql);
                    $count_row = mysqli_fetch_assoc($count_result);
                    $total_products = $count_row['total'];
                    $total_pages = ceil($total_products / $limit);

                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<a href="?page=' . $i . ($category_id > 0 ? '&id=' . $category_id : '') . '">' . $i . '</a> ';
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Featured Section End -->



<!-- Featured Section End -->
<!-- Blog Section Begin -->
<section class="from-blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title from-blog__title">
                    <h2>Blog</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php

            $sql_str = "select * from news order by created_at desc limit 0, 3";
            $result = mysqli_query($conn, $sql_str);
            while ($row = mysqli_fetch_assoc($result)) {

                ?>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="<?= '../admincp/' . $row['thumbnails'] ?>" alt="">
                        </div>
                        <div class=" blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i> | <?= $row['created_at'] ?></li>
                                <li><i class="fa fa-comment-o"></i> 5</li>
                            </ul>
                            <h5><a href="blogdetail.php?id=<?= $row['id'] ?>"><?= $row['title'] ?></a></h5>
                            <p><?= $row['summary'] ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</section>
<!-- Blog Section End -->
<?php
require_once('modules/footer.php');
?>