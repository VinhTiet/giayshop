<?php
require_once '../vendor/autoload.php'; // Include file autoload của Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// Kết nối đến cơ sở dữ liệu
require_once './db/connect.php';

$filter_date = $_GET['filter_date'] ?? '';
$filter_month = $_GET['filter_month'] ?? '';
$filter_year = $_GET['filter_year'] ?? '';

$filter_label = '';
if ($filter_date) {
    $filter_label = "Ngày: " . date('d/m/Y', strtotime($filter_date));
} elseif ($filter_month) {
    $filter_label = "Tháng: " . date('m/Y', strtotime($filter_month));
} elseif ($filter_year) {
    $filter_label = "Năm: " . $filter_year;
} else {
    $filter_label = "Tất Cả";
}

$where_clause = '';
if ($filter_date) {
    $where_clause .= " WHERE DATE(od.created_at) = '$filter_date'";
} elseif ($filter_month) {
    $where_clause .= " WHERE YEAR(od.created_at) = YEAR('$filter_month') AND MONTH(od.created_at) = MONTH('$filter_month')";
} elseif ($filter_year) {
    $where_clause .= " WHERE YEAR(od.created_at) = '$filter_year'";
}

$query = "SELECT od.created_at, p.name AS product_name, SUM(od.num) AS total_quantity, SUM(od.total) AS daily_revenue
          FROM order_details od
          JOIN product p ON od.product_id = p.id
          $where_clause
          GROUP BY od.created_at, p.name
          ORDER BY od.created_at DESC";

$result = mysqli_query($conn, $query);

$revenue_data = [];
$total_revenue = 0; // Khởi tạo biến tổng doanh thu

while ($row = mysqli_fetch_assoc($result)) {
    $revenue_data[] = $row;
    $total_revenue += $row['daily_revenue']; // Tính tổng doanh thu
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);

// Tạo một đối tượng Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Thiết lập tiêu đề cho bảng
$sheet->setCellValue('A1', 'Thống kê doanh thu');
$sheet->mergeCells('A1:D1'); // Gộp các ô trong hàng đầu tiên
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center'); // Căn giữa tiêu đề
$sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true); // Đặt kích thước font và in đậm cho tiêu đề

// Thiết lập tiêu đề cho các cột
$sheet->setCellValue('A2', 'Ngày');
$sheet->setCellValue('B2', 'Tên Sản Phẩm');
$sheet->setCellValue('C2', 'Số Lượng');
$sheet->setCellValue('D2', 'Doanh Thu (VND)');
$sheet->getStyle('A2')->getAlignment()->setHorizontal('center'); // Căn giữa tiêu đề
$sheet->getStyle('B2')->getAlignment()->setHorizontal('center'); // Căn giữa tiêu đề
$sheet->getStyle('C2')->getAlignment()->setHorizontal('center'); // Căn giữa tiêu đề
$sheet->getStyle('D2')->getAlignment()->setHorizontal('center'); // Căn giữa tiêu đề
$sheet->getStyle('A2')->getFont()->setSize(13)->setBold(true); // Đặt kích thước font và in đậm cho tiêu đề
$sheet->getStyle('B2')->getFont()->setSize(13)->setBold(true); // Đặt kích thước font và in đậm cho tiêu đề
$sheet->getStyle('C2')->getFont()->setSize(13)->setBold(true); // Đặt kích thước font và in đậm cho tiêu đề
$sheet->getStyle('D2')->getFont()->setSize(13)->setBold(true); // Đặt kích thước font và in đậm cho tiêu đề
// Đặt dữ liệu từ mảng vào bảng Excel
$row = 3;
foreach ($revenue_data as $data) {
    $sheet->setCellValue('A' . $row, $data['created_at']);
    $sheet->setCellValue('B' . $row, $data['product_name']);
    $sheet->setCellValue('C' . $row, $data['total_quantity']);
    $sheet->setCellValue('D' . $row, $data['daily_revenue']);
    $row++;
}

// Thiết lập chiều rộng cho các cột
$sheet->getColumnDimension('A')->setWidth(25); // Cột A
$sheet->getColumnDimension('B')->setWidth(30); // Cột B
$sheet->getColumnDimension('C')->setWidth(15); // Cột C
$sheet->getColumnDimension('D')->setWidth(20); // Cột D

// Đặt tổng doanh thu vào dòng cuối cùng
$sheet->setCellValue('A' . $row, 'Tổng Doanh Thu:');
$sheet->setCellValue('D' . $row, $total_revenue);
// Định dạng cho ô chứa tổng doanh thu
$sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true); // In đậm cho ô chứa tổng doanh thu

// Tạo một đối tượng Writer để xuất ra file Excel
$writer = new Xlsx($spreadsheet);

// Lưu file Excel
$filename = 'BaoCaoDoanhThu.xlsx';
$writer->save($filename);

// Tải file Excel xuống
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');