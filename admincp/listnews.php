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
            <h6 class="m-0 font-weight-bold text-primary">Tin Tức</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tiêu Đề</th>
                            <th>Ảnh Đại Diện</th>
                            <th>Danh Mục</th>
                            <th>Chức Năng</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require ("./db/connect.php");
                        $sql_str = "select *, news.id as nid from news,
                            newscategory where news.newscategory_id = newscategory.id  order by news.created_at";
                        $result = mysqli_query($conn, $sql_str);
                        $stt = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $stt++;
                            ?>

                            <tr>
                                <td><?= $stt ?></td>
                                <td><?= $row['title'] ?></td>
                                <td><img src='<?= $row['thumbnails'] ?>' height='100px' /></td>
                                <td><?= $row['name'] ?></td>

                                <td>
                                    <a class="btn btn-primary" href="editnews.php?id=<?= $row['nid'] ?>">Chỉnh Sửa </a> |
                                    <a class="btn btn-danger" href="deletenews.php?id=<?= $row['nid'] ?>"
                                        onclick="return confirm('Bạn chắc chắn muốn xóa tin tức này?');">Xóa</a>
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