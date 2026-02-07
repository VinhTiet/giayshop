<?php
    require('./modules/header.php');

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
                                <h1 class="h4 text-gray-900 mb-4">Thêm Tin Tức</h1>
                            </div>
                            <form class="user" method="post" action="addnews2.php" enctype="multipart/form-data">

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="name" name="name"
                                        placeholder="Tiêu Đề Tin Tức">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tóm Tắt Tin Tức</label>
                                    <textarea name="summary" class="form-control">

                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nội Dung Tin</label>
                                    <textarea name="description" class="form-control">

                                    </textarea>
                                </div>
                               
            
                                <div class="form-group">
                                    <label class="form-label">Ảnh Đại Diện Cho Tin</label>
                                    <input type="file" class="" name="thumbnails"
                                    id="thumbnails">
                                    
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Danh Mục Tin</label>
                                    <select class="form-control" name="category" id="category">
                                        <option>Chọn Danh Mục</option>
                                        <?php
                                            require("./db/connect.php");
                                            $sql_str = "select * from newscategory order by name";
                                            $result = mysqli_query($conn, $sql_str);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                        <?php } ?>


                                    </select>
                                </div>
                                
                                <button class="btn btn-primary">Thêm Mới</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?php
    
     require('./modules/footer.php');
?>