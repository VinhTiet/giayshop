<?php


$id = $_GET['id'];

require ("./db/connect.php");
$sql_str = "select * from news where id=$id";
$res = mysqli_query($conn, $sql_str);

$news = mysqli_fetch_assoc($res);
if (isset($_POST['btnUpdate'])) {
    $name = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $description = $_POST['description'];
    $summary = $_POST['summary'];
    //$thumbnail = $_POST['thumbnail'];
    $category = $_POST['category'];


    if (!empty($_FILES['thumbnails']['name'])) {
        unlink($news['thumbnails']);


        // $imgs = '';
        // for($i=0; $i<$countfiles; $i++) {
        $filename = $_FILES['thumbnails']['name'];
        ## location
        $location = "uploads/news/" . uniqid() . $filename;

        $extension = pathinfo($location, PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        ## file upload allowed extensions
        $valid_extensions = array("jpg", "jpeg", "png");

        $response = 0;
        ## check file extension
        if (in_array(strtolower($extension), $valid_extensions)) {
            ## them vao csdl - them thanh cong moi upload len
            ## upload file
            if (move_uploaded_file($_FILES['thumbnails']['tmp_name'], $location)) {
                //echo "file name : ".$filename."<br/>";
                //$totalFileUploaded++;
                // $imgs .= $location . ";";

            }
        }
        // }
        //echo substr($imgs, 0, -1); exit;

        $sql_str = "UPDATE `news`
            SET `title`='$name', 
            `slug`='$slug', 
            `description`='$description',
            `summary`='$summary', 
            `thumbnails`='$location', 
            `newscategory_id`=$category, 
            `updated_at` = now()
            WHERE `id`=$id";
    } else {

        $sql_str = "UPDATE `news` 
            SET `title`='$name', 
            `slug`='$slug', 
            `description`='$description',
            `summary`='$summary', 
            `newscategory_id`=$category, 
            `updated_at` = now()
            WHERE `id`=$id";
    }


    //echo $sql_str; exit;
    mysqli_query($conn, $sql_str);

    header("location: listnews.php");
} else {
    require ('./modules/header.php');
    if ($_SESSION['admin']['type'] != 'Admin') {
        echo "<h3>Bạn Không Có Quyền Chỉnh Sửa Nội Dung Này</h3>";

    } else {

        ?>

        <div>
            <div class="container">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Cập Nhật Tin Tức</h1>
                                    </div>
                                    <form class="user" method="post" action="#" enctype="multipart/form-data">

                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="title" name="title"
                                                placeholder="Tên Sản Phẩm" value="<?= $news['title'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Tóm Tắt Tin</label>
                                            <textarea name="summary" class="form-control">  <?= $news['summary'] ?>"
                                   
                                            </textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Nội Dung Tin</label>
                                            <textarea name="description" class="form-control"> <?= $news['description'] ?>"
                                        
                                            </textarea>
                                        </div>

                                        <div class="form-group row">


                                            <div class="form-group">
                                                <label class="form-label">Ảnh Đại Diện</label>
                                                <input type="file" class="" name="thumbnails" id="thumbnails">
                                                <br>
                                                <br>
                                                Ảnh Hiện Tại:

                                                <?php $thumbnails = $news['thumbnails']; ?>
                                                <img src='<?= $thumbnails ?>' height='100px' />";

                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Danh Mục Tin</label>
                                                <select class="form-control" name="category" id="category">
                                                    <option>Chọn Danh Mục</option>
                                                    <?php
                                                    //require("./db/connect.php");
                                                    $sql_str = "select * from newscategory order by name";
                                                    $result = mysqli_query($conn, $sql_str);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        ?>
                                                        <option value="<?php echo $row['id']; ?>" <?php
                                                          if ($row['id'] == $news['newscategory_id']) {
                                                              echo "selected=true";
                                                          }
                                                          ?>>
                                                            <?php echo $row['name']; ?></option>
                                                    <?php } ?>


                                                </select>
                                            </div>

                                            <button class="btn btn-primary" name="btnUpdate">Cập Nhật</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <?php
    }
    require ('./modules/footer.php');
}
?>