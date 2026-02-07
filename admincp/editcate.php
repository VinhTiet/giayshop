<?php
   

    $id = $_GET['id'];

    require("./db/connect.php");    
    $sql_str = "select * from category where id=$id";
    $res = mysqli_query($conn, $sql_str);

    $cate = mysqli_fetch_assoc($res);
    if(isset($_POST['btnUpdate'])) {
        $name = $_POST['name'];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/','-', $name)));
        $status = $_POST['status'];
        $sql_str = "update category set name='$name', slug='$slug', status='$status' where id=$id";
        mysqli_query($conn, $sql_str);

        header("location: listcate.php");
    }else {
        require('./modules/header.php');
        if($_SESSION['admin']['type'] != 'Admin') {
            echo "<h3>Bạn Không Có Quyền Chỉnh Sửa Nội Dung Này</h3>";
               
        }else {
    
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
                                <h1 class="h4 text-gray-900 mb-4">Cập Nhật Danh Mục Sản Phẩm</h1>
                            </div>
                            <form class="user" method="post" action="#">

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="name" name="name"
                                        placeholder="Tên Danh Mục" value="<?=$cate['name']?>">
                                      
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="status" name="status"
                                        placeholder="Trạng Thái" value="<?=$cate['status']?>">
                                      
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
     require('./modules/footer.php');
}
?>
