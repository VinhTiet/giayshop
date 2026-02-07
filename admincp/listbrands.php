<?php
require ('./modules/header.php');
?>

<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh Sách Thương Hiệu</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tên Thương Hiệu</th>
                            <th>Slug</th>
                            <th>Trạng Thái</th>
                            <th>Chức Năng</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require ("./db/connect.php");
                        $sql_str = "select * from brand order by id";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>

                            <tr>

                                <td><?= $row['name'] ?></td>
                                <td><?= $row['slug'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td>
                                    <a class="btn btn-primary" href="editbrand.php?id=<?= $row['id'] ?>">Chỉnh Sửa </a> |
                                    <a class="btn btn-danger" href="deletebrand.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa mục này?');">Xóa</a>
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