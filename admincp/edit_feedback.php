<?php
require ('./db/connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_str = "SELECT * FROM feedback WHERE id = $id";
    $result = mysqli_query($conn, $sql_str);
    $feedback = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $_POST['response'];
    $sql_update = "UPDATE feedback SET response = '$response' WHERE id = $id";
    mysqli_query($conn, $sql_update);
    header('location: feedback.php');
}
require ('./modules/header.php');
?>


<div>
    <div class="container">
        <h2>Phản Hồi Của Khách Hàng</h2>
        <p><strong>Tên Khách Hàng:</strong> <?= $feedback['name'] ?></p>
        <p><strong>Email:</strong> <?= $feedback['email'] ?></p>
        <p><strong>Số Điện Thoại:</strong> <?= $feedback['phone_number'] ?></p>
        <p><strong>Phản Hồi Của Khách Hàng:</strong> <?= $feedback['note'] ?></p>
        <p><strong>Ngày Tạo:</strong> <?= $feedback['created_at'] ?></p>
        <form method="POST">
            <div class="form-group">
                <label for="response">Trả Lời:</label>
                <textarea class="form-control" id="response" name="response"
                    rows="4"><?= $feedback['response'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Gửi Phản Hồi</button>
        </form>

    </div>

</div>

<?php
require ('./modules/footer.php');

?>