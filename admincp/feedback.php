<?php
require ('./modules/header.php');

?>
<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Phản Hồi Của Khách Hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên Khách Hàng</th>
                            <th>Email</th>
                            <th>Số Điện Thoại</th>
                            <th>Phản Hồi Của Khách Hàng</th>
                            <th>Phản Hồi Của Nhân Viên</th>
                            <th>Ngày Tạo</th>
                            <th>Chức Năng</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require ("./db/connect.php");
                        $sql_str = "select * from feedback  order by created_at";
                        $result = mysqli_query($conn, $sql_str);
                        $stt = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $stt++;
                            ?>

                            <tr>
                                <td><?= $stt ?></td>

                                <td><?= $row['name'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['phone_number'] ?></td>
                                <td><?= $row['note'] ?></td>
                                <td><?= $row['response'] ?></td>
                                <td><?= $row['created_at'] ?></td>
                                <td>
                                    <a class="btn btn-primary" href="edit_feedback.php?id=<?= $row['id'] ?>">Xem</a>
                                    <a class="btn btn-danger" href="delete_feedback.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa phản hồi này?');">Xóa</a>
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
require ('./modules/footer.php');
?>