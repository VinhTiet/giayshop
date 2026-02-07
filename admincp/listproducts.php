<?php
require ('./modules/header.php');

function thumbnail($arrstr, $height)
{
    //$arr = $arrstr.split(',');
    $arr = explode(';', $arrstr);
    return "<img src='$arr[0]' height='$height' />";
}
?>

<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh Sách Sản Phẩm</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tên Sản Phẩm</th>
                            <th>Ảnh Sản Phẩm</th>
                            <th>Danh Mục</th>
                            <th>Thương Hiệu</th>
                            <th>Chức Năng</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require ("./db/connect.php");
                        $sql_str = "select product.id as pid, product.name as pname, thumbnail, category.name as cname, brand.name as bname from product,
                            category, brand where product.category_id = category.id and product.brand_id = brand.id order by product.name";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>

                            <tr>
                                <td><?= $row['pname'] ?></td>
                                <td><?= thumbnail($row['thumbnail'], "200px") ?></td>
                                <td><?= $row['cname'] ?></td>
                                <td><?= $row['bname'] ?></td>

                                <td>
                                    <a class="btn btn-primary" href="editproduct.php?id=<?= $row['pid'] ?>">Chỉnh Sửa </a> |
                                    <a class="btn btn-danger" href="deleteproduct.php?id=<?= $row['pid'] ?>"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                                </td>
                            </tr>
                            <?php
                            if (isset($_SESSION['message'])) {
                                echo "<h3 class='message'>" . $_SESSION['message'] . "</h3>";
                                unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
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

require ('./modules/footer.php');
?>