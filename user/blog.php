<?php
session_start();

$is_homepage = false;
require_once('modules/header.php');
require("./db/connect.php");

// Số lượng bản ghi trên mỗi trang
$records_per_page = 6;

// Trang hiện tại (mặc định là trang 1)
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Tính OFFSET để lấy bản ghi từ cơ sở dữ liệu
$offset = ($current_page - 1) * $records_per_page;

// Truy vấn để lấy tổng số bản ghi trong cơ sở dữ liệu
$total_records_query = "SELECT COUNT(*) AS total FROM news";
$total_records_result = mysqli_query($conn, $total_records_query);
$total_records_row = mysqli_fetch_assoc($total_records_result);
$total_records = $total_records_row['total'];

// Tính tổng số trang
$total_pages = ceil($total_records / $records_per_page);

// Truy vấn để lấy dữ liệu cho trang hiện tại
$sql_str = "SELECT * FROM news ORDER BY id LIMIT $records_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql_str);

// Hiển thị dữ liệu và các liên kết phân trang
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
                        <span>Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Blog Section Begin -->
<section class="blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="blog__sidebar">
                    <div class="blog__sidebar__search">
                        <form action="#">
                            <input type="text" placeholder="Tìm Kiếm...">
                            <button type="submit"><span class="icon_search"></span></button>
                        </form>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Danh Mục Tin</h4>
                        <ul>
                            <li><a href="#">Tất Cả</a></li>
                            <?php
                            $sql_str2 = "select * from newscategory order by id";
                            $result2 = mysqli_query($conn, $sql_str2);
                            while ($row2 = mysqli_fetch_assoc($result2)) {
                                ?>
                            <li><a href="#"><?= $row2['name'] ?> (20)</a></li>
                            <?php } ?>
                            <!-- <li><a href="#">Food (5)</a></li>
                                <li><a href="#">Life Style (9)</a></li>
                                <li><a href="#">Travel (10)</a></li> -->
                        </ul>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Tin Mới</h4>
                        <div class="blog__sidebar__recent">

                            <?php
                            $sql_str3 = "select * from news order by created_at desc limit 0,3";
                            $result3 = mysqli_query($conn, $sql_str3);
                            while ($row3 = mysqli_fetch_assoc($result3)) {
                                ?>
                            <a href="#" class="blog__sidebar__recent__item">
                                <div class="blog__sidebar__recent__item__pic">
                                    <img src="<?= '../admincp/' . $row3['thumbnails'] ?>" width="70px" alt="">
                                </div>
                                <div class="blog__sidebar__recent__item__text">
                                    <h6><?= $row3['title'] ?></h6>
                                    <span><?= $row3['created_at'] ?></span>
                                </div>
                            </a>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Tìm Kiếm</h4>
                        <div class="blog__sidebar__item__tags">
                            <?php
                            $sql_str = "select * from newscategory order by id";
                            $result2 = mysqli_query($conn, $sql_str);
                            while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                            <a href="#"><?= $row2['name'] ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="section-title product__discount__title">
                    <h2>Tất Cả Tin Tức</h2>
                </div>
                <div class="row">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="blog__item">
                            <div class="blog__item__pic">
                                <img src="<?= '../admincp/' . $row['thumbnails'] ?>" alt="">
                            </div>
                            <div class="blog__item__text">
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

                <div class="col-lg-12">
                    <div class="product__pagination blog__pagination">
                        <?php for ($page = 1; $page <= $total_pages; $page++) { ?>
                        <a href="?page=<?= $page ?>"><?= $page ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Section End -->
<?php
require_once('modules/footer.php');
?>