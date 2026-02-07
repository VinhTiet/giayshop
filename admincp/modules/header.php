<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('modules/header.php');
// Kiểm tra xem admin đã đăng nhập chưa
if (!isset($_SESSION['admin'])) {
    header("Location: loginform.php");
    exit;
}


if (session_status() === PHP_SESSION_NONE) session_start();

/* Cho phép cả hai kiểu session:
   - Cũ: $_SESSION['admin'] = mảng admin
   - Mới (khuyến nghị): admin_logged_in + admin_id + admin_name
*/
$isLogged = !empty($_SESSION['admin_logged_in']) || !empty($_SESSION['admin']);
if (!$isLogged) {
    header('Location: loginform.php');
    exit;
}

/* Lấy sẵn thông tin admin đang đăng nhập để dùng ở mọi nơi */
$ADMIN_ID   = $_SESSION['admin_id']   ?? ($_SESSION['admin']['id']   ?? null);
$ADMIN_NAME = $_SESSION['admin_name'] ?? ($_SESSION['admin']['name'] ?? '');

/* ✅ Chỉ include header sau khi đã check đăng nhập */
require_once('modules/header.php');


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Quản Trị </title>

    <!-- Custom fonts for this template-->
    <link href="./assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./assets/css/style3.css?v=0" type="text/css">

    <style>
    .number {
        color: red;
        font-weight: 500;
    }

    .btn_filter {
        margin-left: 10px;
        padding: 5px 0;
        width: 60px;
    }

    .revenue-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ddd;
        text-align: left;
    }

    .revenue-table th,
    .revenue-table td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    .revenue-table th {
        background-color: #f2f2f2;
        text-align: left;
    }

    .revenue-table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .total-revenue {
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
        text-align: right;
    }

    .message,
    h3 {
        padding: 10px;
        background-color: #f2dede;
        border: 1px solid #ebccd1;
        color: #a94442;
        border-radius: 4px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: bold;
    }

    h2 {
        text-align: center;
    }

    .filter_revenue {
        text-align: center;
        padding: 18px;
    }

    .filter_revenue label {
        padding: 0 10px;
    }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php require('sidebar.php') ?>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require('topbar.php') ?>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">