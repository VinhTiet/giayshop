<?php
require_once '../vendor/autoload.php'; // Include file autoload của Composer
require_once './db/connect.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Lấy ID từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID không hợp lệ.");
}

// Lấy thông tin nhân viên từ POST
$employee_id = $_POST['employee_id'] ?? 0;

if ($employee_id <= 0) {
    die("ID nhân viên không hợp lệ.");
}

// Lấy tên nhân viên từ bảng admin
$employee_sql = "SELECT name FROM admin WHERE id=$employee_id";
$employee_res = mysqli_query($conn, $employee_sql);
$employee = mysqli_fetch_assoc($employee_res);
$employee_name = $employee['name'] ?? '';

// Tạo mã hóa đơn ngẫu nhiên
function generateInvoiceCode() {
    $timestamp = time();
    $randomString = substr(md5(rand()), 0, 5);
    return 'INV' . $timestamp . $randomString;
}

$invoice_code = generateInvoiceCode();

// Lấy thông tin đơn hàng từ cơ sở dữ liệu
$sql_str = "SELECT * FROM orders WHERE id=$id";
$res = mysqli_query($conn, $sql_str);

if (!$res || mysqli_num_rows($res) == 0) {
    die("Không tìm thấy đơn hàng.");
}

$order = mysqli_fetch_assoc($res);

// Lấy thông tin chi tiết đơn hàng từ cơ sở dữ liệu
$sql_details = "SELECT p.name as pname, od.price as oprice, od.num, (od.price * od.num) as total 
                FROM product p 
                JOIN order_details od ON p.id = od.product_id 
                WHERE od.order_id=$id";
$res_details = mysqli_query($conn, $sql_details);

if (!$res_details) {
    die("Lỗi truy vấn chi tiết đơn hàng.");
}

$order_details = [];
while ($row = mysqli_fetch_assoc($res_details)) {
    $order_details[] = $row;
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);

// Tạo một đối tượng Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Thiết lập tiêu đề cho bảng
$sheet->setCellValue('A1', 'Hóa đơn mua hàng');
$sheet->mergeCells('A1:E1'); // Gộp các ô trong hàng đầu tiên
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center'); // Căn giữa tiêu đề
$sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true); // Đặt kích thước font và in đậm cho tiêu đề

// Thiết lập thông tin hóa đơn
$sheet->setCellValue('A2', 'Mã hóa đơn: ' . $invoice_code);
$sheet->setCellValue('A3', 'Nhân viên xuất hóa đơn: ' . $employee_name);
$sheet->setCellValue('A4', 'Khách hàng: ' . $order['firstname'] . ' ' . $order['lastname']);
$sheet->setCellValue('A5', 'Địa chỉ: ' . $order['address']);
$sheet->setCellValue('A6', 'Số điện thoại: ' . $order['phone_number']);
$sheet->setCellValue('A7', 'Email: ' . $order['email']);
$sheet->setCellValue('A8', 'Trạng thái đơn hàng: ' . $order['status']);

// Thiết lập tiêu đề cho các cột
$sheet->setCellValue('A10', 'STT');
$sheet->setCellValue('B10', 'Sản Phẩm');
$sheet->setCellValue('C10', 'Giá');
$sheet->setCellValue('D10', 'Số Lượng');
$sheet->setCellValue('E10', 'Tổng Tiền');

// Căn giữa và in đậm các tiêu đề cột
$columns = ['A', 'B', 'C', 'D', 'E'];
foreach ($columns as $column) {
    $sheet->getStyle($column . '10')->getAlignment()->setHorizontal('center');
    $sheet->getStyle($column . '10')->getFont()->setSize(13)->setBold(true);
}

// Đặt dữ liệu từ mảng vào bảng Excel
$row = 11;
foreach ($order_details as $index => $data) {
    $sheet->setCellValue('A' . $row, $index + 1);
    $sheet->setCellValue('B' . $row, $data['pname']);
    $sheet->setCellValue('C' . $row, number_format($data['oprice'], 0, '', '.') . ' đ');
    $sheet->setCellValue('D' . $row, $data['num']);
    $sheet->setCellValue('E' . $row, number_format($data['total'], 0, '', '.') . ' đ');
    $row++;
}

// Thiết lập chiều rộng cho các cột
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(20);

// Tạo một đối tượng Writer để xuất ra file Excel
$writer = new Xlsx($spreadsheet);

// Lưu file Excel
$filename = 'HoaDon_' . $invoice_code . '.xlsx';
$writer->save($filename);

// Tải file Excel xuống
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
?>