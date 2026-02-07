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
    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">

                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Thêm Người Dùng</h1>
                            </div>
                            <form class="user" method="post" action="adduser2.php">

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="name" name="name"
                                        placeholder="Tên Người Dùng">
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email" name="email"
                                        placeholder="Email">
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" id="password"
                                        name="password" placeholder="Mật Khẩu">
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="phone_number"
                                        name="phone_number" placeholder="Số Điện Thoại">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="address"
                                        name="address" placeholder="Địa Chỉ">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Quyền:</label>
                                    <select class="form-control" name="type" id="type">
                                        <option>Chọn Quyền</option>
                                        <option value="Admin">Quản Trị</option>
                                        <option value="Staff">Nhân Viên</option>
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
    }
     require('./modules/footer.php');
?>