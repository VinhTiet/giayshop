<?php
session_start();
$is_homepage = false;
require ("./db/connect.php");
// Lấy id danh mục từ URL
$category_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Lấy tên danh mục
$category_name = "Sản Phẩm";
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

require_once ('modules/header.php');
?>

<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2><?= htmlspecialchars($category_name) ?></h2>
                </div>

            </div>
        </div>
        <div class="row featured__filter">
            <?php
            // Truy vấn sản phẩm dựa trên danh mục đã chọn
            if ($category_id > 0) {
                $sql_str = "SELECT product.id AS pid, product.name AS pname, thumbnail, price, category.slug AS cslug 
                            FROM product 
                            JOIN category ON product.category_id = category.id 
                            WHERE category.id = $category_id";
            } else {
                $sql_str = "SELECT product.id AS pid, product.name AS pname, thumbnail, price, category.slug AS cslug 
                            FROM product 
                            JOIN category ON product.category_id = category.id";
            }

            $result = mysqli_query($conn, $sql_str);
            while ($row = mysqli_fetch_assoc($result)) {
                $thumbnail_arr = explode(';', $row['thumbnail']);
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mix <?= $row['cslug'] ?>">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg"
                            data-setbg="<?= "../admincp/" . htmlspecialchars($thumbnail_arr[0]) ?>">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart" name="atcbtn"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a
                                    href="shopdetail.php?id=<?= htmlspecialchars($row['pid']) ?>"><?= htmlspecialchars($row['pname']) ?></a>
                            </h6>
                            <h5><?= number_format($row['price'], 0, '', '.') ?><sup>đ</sup></h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php
require_once ('modules/footer.php');
?>