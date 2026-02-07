<?php 
    session_start();

    $is_homepage = false;
    require_once('modules/header.php');

    
    $keyword = $_GET['keyword'];
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="./assets/img/banner2.png">
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
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Danh Mục Sản Phẩm</h4>
                        <ul>
                            <?php
                                    require("./db/connect.php");
                                    $sql_str = "select * from category order by id";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>

                            <li><a href="#"><?=$row['name']?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="sidebar__item">
                        <h4>Giá</h4>
                        <div class="price-range-wrap">
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="10" data-max="540">
                                <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                            </div>
                            <div class="range-slider">
                                <div class="price-input">
                                    <input type="text" id="minamount">
                                    <input type="text" id="maxamount">
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="sidebar__item">
                        <div class="latest-product__text">
                            <h4>Sản Phẩm Mới</h4>
                            <div class="latest-product__slider owl-carousel">
                                <div class="latest-prdouct__slider__item">
                                    <?php
                                            $sql_str = "select * from product order by created_at desc limit 0, 3;";
                                            $result = mysqli_query($conn, $sql_str);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $thumbnail_arr = explode(';', $row['thumbnail']);
                                        ?>
                                    <a href="shopdetail.php?id=<?=$row['id']?>" class="latest-product__item">
                                        <div class="latest-product__item__pic">
                                            <img src="<?="../admincp/".$thumbnail_arr[0]?>" alt="">
                                        </div>
                                        <div class="latest-product__item__text">
                                            <h6><?=$row['name']?></h6>
                                            <span><?=number_format($row['price'], 0, '', '.')?><sup>đ</sup></span>
                                        </div>
                                    </a>
                                    <?php
                                            }
                                        ?>

                                </div>
                                <div class="latest-prdouct__slider__item">
                                    <?php
                                            $sql_str = "select * from product order by created_at desc limit 3, 3;";
                                            $result = mysqli_query($conn, $sql_str);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $thumbnail_arr = explode(';', $row['thumbnail']);
                                        ?>
                                    <a href="shopdetail.php?id=<?=$row['id']?>" class="latest-product__item">
                                        <div class="latest-product__item__pic">
                                            <img src="<?="../admincp/".$thumbnail_arr[0]?>" alt="">
                                        </div>
                                        <div class="latest-product__item__text">
                                            <h6><?=$row['name']?></h6>
                                            <span><?=number_format($row['price'], 0, '', '.')?><sup>đ</sup></span>
                                        </div>
                                    </a>
                                    <?php
                                            }
                                        ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                <h3>Kết Quả Tìm Kiếm</h3>
                <div class="filter__item">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="filter__sort">
                                <span>Sắp Xếp Theo</span>
                                <select>
                                    <option value="0">Mặc Định</option>
                                    <option value="0">Mặc Định</option>
                                </select>
                            </div>
                        </div>

                        <?php
                            // Chỉ tìm kiếm theo tên sản phẩm, không ràng buộc danh mục hay điều kiện khác
                            $sql_str = "SELECT * FROM product WHERE name LIKE '%$keyword%' ORDER BY name";
                            $result = mysqli_query($conn, $sql_str);
                        ?>

                        <div class="col-lg-4 col-md-4">
                            <div class="filter__found">
                                <h6> Có <span><?=mysqli_num_rows($result)?></span>Sản Phẩm</h6>
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
                    <?php
                             while ($row = mysqli_fetch_assoc($result)) {
                                $thumbnail_arr = explode(';', $row['thumbnail']);
                        ?>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="<?="../admincp/".$thumbnail_arr[0]?>">
                                <ul class="product__item__pic__hover">
                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <h6><a href="shopdetail.php?id=<?=$row['id']?>"><?=$row['name']?></a></h6>
                                <h5><?=number_format($row['price'], 0, '', '.')?><sup>đ</sup></h5>
                            </div>
                        </div>
                    </div>
                    <?php }?>

                </div>
                <div class="product__pagination">
                    <a href="#">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#"><i class="fa fa-long-arrow-right"></i></a>
                </div>
                <br>
                <div class="product__discount">
                    <div class="section-title product__discount__title">
                        <h2>Giảm Sốc</h2>
                    </div>
                    <div class="row">
                        <div class="product__discount__slider owl-carousel">
                            <?php
                                $sql_str = "select product.id as pid, product.name as pname, category.name as cname, round((price-discount)/price*100) as dc, thumbnail, price, discount from product, category 
                                where product.category_id = category.id order by dc desc limit 0,6;";
                                $result = mysqli_query($conn, $sql_str);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $thumbnail_arr = explode(';', $row['thumbnail']);
                            ?>
                            <div class="col-lg-4">
                                <div class="product__discount__item">
                                    <div class="product__discount__item__pic set-bg"
                                        data-setbg="<?="../admincp/".$thumbnail_arr[0]?>">
                                        <div class="product__discount__percent"><?=$row['dc']?>%</div>
                                        <ul class="product__item__pic__hover">
                                            <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                            <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="product__discount__item__text">
                                        <span><?=$row['cname']?></span>
                                        <h5><a href="shopdetail.php?id=<?=$row['pid']?>"><?=$row['pname']?></a></h5>
                                        <div class="product__item__price">
                                            <?=number_format($row['discount'], 0, '', '.')?><sup>đ</sup>
                                            <span><?=number_format($row['price'], 0, '', '.')?><sup>đ</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Section End -->
<?php 
    require_once('modules/footer.php');
?>