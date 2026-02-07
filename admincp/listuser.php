<?php

session_start();
    require('./modules/header.php');

    if ($_SESSION['admin']['type'] != 'Admin') {
        echo "<h2>Bạn Không Có Quyền Truy Cập Nội Dung Này</h2>";
    } else {
        if (isset($_SESSION['error_message'])) {
            echo '<h2>' . $_SESSION['error_message'] . '</h2>';
            unset($_SESSION['error_message']); // Xóa thông báo lỗi sau khi hiển thị
        }
?>

<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh Sách Người Dùng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Loại Tài Khoản</th>
                            <th>Trạng Thái</th>
                            <th>Chức Năng</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require ("./db/connect.php");
                        $sql_str = "select * from admin
                            order by created_at";
                        $result = mysqli_query($conn, $sql_str);
                        $stt = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $stt++;
                            ?>

                            <tr>
                                <td><?= $stt ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['type'] ?></td>
                                <td><?= $row['status'] ?></td>

                                <td>
                                    <a class="btn btn-primary" href="editadmin.php?id=<?= $row['id'] ?>">Chỉnh Sửa </a> |
                                    <a class="btn btn-danger" href="deleteuser.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa tài khoản này?');">Xóa</a>
                                </td>
                            </tr>
                            <?php
                            if (isset($_SESSION['message'])) {
                                echo "<h3>" . $_SESSION['message'] . "</h3>";
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
    }
require ('./modules/footer.php');
?>