<?php
require_once '../vendor/autoload.php'; // Include file autoload của Composer
// Kết nối đến cơ sở dữ liệu
require_once './db/connect.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

$id = $_POST['id'];
$employee_id = $_POST['employee_id'];

// Lấy thông tin đơn hàng
$sql_order = "SELECT * FROM orders WHERE id=$id";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

// Lấy chi tiết đơn hàng
$sql_details = "SELECT *, product.name as pname, order_details.price as oprice FROM product, order_details WHERE product.id = order_details.product_id AND order_id=$id";
$res_details = mysqli_query($conn, $sql_details);

// Lấy thông tin nhân viên
$sql_employee = "SELECT name FROM admin WHERE id=$employee_id";
$res_employee = mysqli_query($conn, $sql_employee);
$employee = mysqli_fetch_assoc($res_employee);

// Tạo đối tượng Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


// Thiết lập thông tin khách hàng
$sheet->setCellValue('A1', 'Khách Hàng:');
$sheet->setCellValue('B1', $order['firstname'] . ' ' . $order['lastname']);
$sheet->setCellValue('A2', 'Địa Chỉ:');
$sheet->setCellValue('B2', $order['address']);
$sheet->setCellValue('A3', 'Số Điện Thoại:');
$sheet->setCellValue('B3', $order['phone_number']);
$sheet->setCellValue('A4', 'Email:');
$sheet->setCellValue('B4', $order['email']);

// Thiết lập tiêu đề cột
$sheet->setCellValue('A6', 'STT');
$sheet->setCellValue('B6', 'SẢN PHẨM');
$sheet->setCellValue('C6', 'GIÁ');
$sheet->setCellValue('D6', 'SỐ LƯỢNG');
$sheet->setCellValue('E6', 'TIỀN');

// Định dạng tiêu đề cột
$headerStyle = [
    'font' => [
        'bold' => true,
        'size' => 12,
        'color' => ['argb' => 'FFFFFF'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => '0000FF'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
        ],
    ],
];
$sheet->getStyle('A6:E6')->applyFromArray($headerStyle);

// Căn giữa và in đậm các tiêu đề cột
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Điền dữ liệu vào các hàng
$rowNum = 7;
$stt = 1;
$total = 0;

while ($detail = mysqli_fetch_assoc($res_details)) {
    $total += $detail['num'] * $detail['oprice'];
    $sheet->setCellValue('A' . $rowNum, $stt++);
    $sheet->setCellValue('B' . $rowNum, $detail['pname']);
    $sheet->setCellValue('C' . $rowNum, number_format($detail['oprice'], 0, '', '.'));
    $sheet->setCellValue('D' . $rowNum, $detail['num']);
    $sheet->setCellValue('E' . $rowNum, number_format($detail['num'] * $detail['oprice'], 0, '', '.'));
    $rowNum++;
}

// Thêm tổng tiền vào cuối bảng
$sheet->setCellValue('D' . $rowNum, 'Tổng Tiền');
$sheet->setCellValue('E' . $rowNum, number_format($total, 0, '', '.'));

// Thêm tên nhân viên vào báo cáo
$rowNum += 2;
$sheet->setCellValue('A' . $rowNum, 'Nhân viên xuất hóa đơn:');
$sheet->setCellValue('B' . $rowNum, $employee['name']);

// Tạo file Excel
$writer = new Xlsx($spreadsheet);
$filename = 'HoaDon_' . $order['id'] . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
