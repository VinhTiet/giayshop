<?php
session_start();
$id = $_GET['id'];

require ("./db/connect.php");

// Sử dụng prepared statement để tránh SQL Injection
$sql_str = "SELECT * FROM admin WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql_str);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$admin = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (isset($_POST['btnUpdate'])) {
    // Làm sạch và kiểm tra dữ liệu đầu vào
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    // Kiểm tra xem mật khẩu có được nhập mới hay không
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu mới
    } else {
        $password = $admin['password']; // Giữ mật khẩu cũ nếu không có mật khẩu mới
    }

    // Sử dụng prepared statement để cập nhật dữ liệu
    $sql_str = "UPDATE admin SET name = ?, email = ?, phone_number = ?, password = ?, address = ?, type = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql_str);
    mysqli_stmt_bind_param($stmt, 'ssssssi', $name, $email, $phone_number, $password, $address, $type, $id);

    if (mysqli_stmt_execute($stmt)) {
        // Sau khi cập nhật thành công, lấy lại thông tin người dùng để hiển thị trong form
        $sql_str = "SELECT * FROM admin WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql_str);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        header("Location: listuser.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
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
                                        <h1 class="h4 text-gray-900 mb-4">Cập Nhật Người Dùng</h1>
                                    </div>
                                    <form class="user" method="post" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="name" name="name"
                                                placeholder="Tên Người Dùng" value="<?= htmlspecialchars($admin['name']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="email" name="email"
                                                placeholder="Email" value="<?= htmlspecialchars($admin['email']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password"
                                                name="password" placeholder="Mật Khẩu">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="phone_number"
                                                name="phone_number" placeholder="Số Điện Thoại"
                                                value="<?= htmlspecialchars($admin['phone_number'] ?? '') ?>">
                                                

                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="address"
                                                name="address" placeholder="Địa Chỉ"
                                                value="<?= htmlspecialchars($admin['address'] ?? '') ?>">
                                                

                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Quyền:</label>
                                            <select class="form-control" name="type" id="type">
                                                <option>Chọn Quyền</option>
                                                <option value="Admin" <?= ($admin['type'] == 'Admin') ? 'selected' : '' ?>>
                                                    Quản Trị</option>
                                                <option value="Staff" <?= ($admin['type'] == 'Staff') ? 'selected' : '' ?>>
                                                    Nhân Viên</option>
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
