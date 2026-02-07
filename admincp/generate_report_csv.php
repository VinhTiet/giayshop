<?php
// admincp/generate_report_csv.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db/connect.php';

// ==== Nhận & áp dụng bộ lọc ====
$filter_date  = $_GET['filter_date']  ?? '';
$filter_month = $_GET['filter_month'] ?? '';
$filter_year  = $_GET['filter_year']  ?? '';

$where = '';
$label = 'Tất Cả';
if ($filter_date) {
    $d = mysqli_real_escape_string($conn, $filter_date);
    $where = " WHERE DATE(od.created_at) = '$d' ";
    $label = 'Ngày: ' . date('d/m/Y', strtotime($filter_date));
} elseif ($filter_month) {
    $m = mysqli_real_escape_string($conn, $filter_month);
    $where = " WHERE YEAR(od.created_at) = YEAR('$m') AND MONTH(od.created_at) = MONTH('$m') ";
    $label = 'Tháng: ' . date('m/Y', strtotime($filter_month));
} elseif ($filter_year) {
    $y = (int)$filter_year;
    $where = " WHERE YEAR(od.created_at) = $y ";
    $label = 'Năm: ' . $y;
}

// ==== Lấy dữ liệu ====
$sql = "
SELECT
  p.name AS product_name,
  DATE(od.created_at) AS order_date,
  SUM(od.num)   AS total_num,
  SUM(od.total) AS daily_revenue
FROM order_details od
JOIN product p ON p.id = od.product_id
$where
GROUP BY p.name, DATE(od.created_at)
ORDER BY DATE(od.created_at) DESC, p.name ASC
";
$rs = mysqli_query($conn, $sql);

$rows = [];
$total = 0;
while ($r = mysqli_fetch_assoc($rs)) {
    $r['total_num']     = (int)$r['total_num'];
    $r['daily_revenue'] = (float)$r['daily_revenue'];
    $total += $r['daily_revenue'];
    $rows[] = $r;
}
mysqli_close($conn);
$filename = 'BaoCaoDoanhThu.csv';
header('Content-Type: text/csv; charset=UTF-16LE');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$out = fopen('php://output', 'w');

// Ghi BOM UTF-16LE
fwrite($out, "\xFF\xFE");

function utf16le($text) {
  return mb_convert_encoding($text, 'UTF-16LE', 'UTF-8');
}

// Header
fwrite($out, utf16le("BÁO CÁO DOANH THU\r\n"));
fwrite($out, utf16le("Bộ lọc: $label\r\n\r\n"));
fwrite($out, utf16le("Ngày;Tên sản phẩm;Số lượng;Doanh thu (VND)\r\n"));

foreach ($rows as $r) {
  $line = implode(';', [
      $r['order_date'],
      $r['product_name'],
      $r['total_num'],
      number_format($r['daily_revenue'], 0, '', '')
  ]);
  fwrite($out, utf16le($line . "\r\n"));
}

fwrite($out, utf16le("\r\n;;Tổng;" . number_format($total, 0, '', '') . "\r\n"));
fclose($out);
exit;

