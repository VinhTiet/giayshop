<?php
    // Lay du lieu tu form
    require("./db/connect.php");
    $name = $_POST['name'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/','-', $name)));
    $status = $_POST['status'];
    
    $sql_str = "INSERT INTO `newscategory` (`name`, `slug`, `status`) VALUES
    ('$name','$slug', '$status');";
    //echo $sql_str; exit;
    mysqli_query($conn, $sql_str);

    header("location: listnewscate.php");
?>