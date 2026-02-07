<?php
// cate.php
session_start();

/* KẾT NỐI DB: đi ra 1 cấp rồi vào /db/connect.php
   -> C:\xampp\htdocs\webbanhang\db\connect.php */

// ==== TÌM connect.php THEO NHIỀU ỨNG VIÊN, DỪNG KHI THẤY ====
$CANDIDATES = [
  __DIR__ . '/../db/connect.php',            // webbanhang/db/connect.php (thường đúng)
  __DIR__ . '/db/connect.php',               // webbanhang/admincp/db/connect.php
  dirname(__DIR__, 2) . '/db/connect.php',   // webbanhang/../db/connect.php (phòng nest khác)
  dirname(__DIR__) . '/connect.php',         // webbanhang/connect.php (nếu không có thư mục db)
  $_SERVER['DOCUMENT_ROOT'] . '/webbanhang/db/connect.php', // nếu site là /webbanhang
  $_SERVER['DOCUMENT_ROOT'] . '/db/connect.php',            // nếu db ở ngay htdocs/db
];

$__found = false;
foreach ($CANDIDATES as $__p) {
  if (file_exists($__p)) {
    require_once $__p;
    $__found = true;
    break;
  }
}

if (!$__found) {
  // Báo rõ đã thử những đường dẫn nào để bạn nhìn và đặt đúng connect.php
  header('Content-Type: text/plain; charset=utf-8');
  echo "Không tìm thấy db/connect.php. Đã thử các đường dẫn sau:\n";
  foreach ($CANDIDATES as $__p) echo " - " . $__p . "\n";
  exit;
}

// Kiểm tra $conn
if (!isset($conn) || !($conn instanceof mysqli)) {
  die('Kết nối DB ($conn) chưa sẵn sàng. Mở file connect.php và đảm bảo có: $conn = mysqli_connect(...);');
}


/* Hàm tạo slug tiếng Việt */
function vn_slug($str){
  $str = trim($str);
  if ($str === '') return 'danh-muc';
  $str = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$str);
  $str = strtolower($str);
  $str = preg_replace('/[^a-z0-9]+/','-',$str);
  $str = trim($str,'-');
  return $str ?: 'danh-muc';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: addcate.php");  // <<< đổi tên đúng file form
  exit;
}

$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
$status = isset($_POST['status']) ? trim($_POST['status']) : null;
$gender_id = isset($_POST['gender_id']) ? (int)$_POST['gender_id'] : 0;

if ($name === '' || $gender_id <= 0) {
  $_SESSION['flash_error'] = "Vui lòng nhập tên danh mục và chọn giới tính.";
  header("Location: addcate.php");   // <<< đổi tên đúng file form
  exit;
}

if ($slug === '') $slug = vn_slug($name);

/* Đảm bảo slug duy nhất */
$baseSlug = $slug; $i = 2;
$check = mysqli_prepare($conn, "SELECT 1 FROM category WHERE slug=? LIMIT 1");
while (true) {
  mysqli_stmt_bind_param($check, "s", $slug);
  mysqli_stmt_execute($check);
  $exists = mysqli_stmt_get_result($check);
  if ($exists && mysqli_fetch_row($exists)) {
    $slug = $baseSlug . '-' . $i++;
  } else break;
}
mysqli_stmt_close($check);

/* Insert */
$stmt = mysqli_prepare($conn, "INSERT INTO category(name, slug, status, gender_id) VALUES(?,?,?,?)");
mysqli_stmt_bind_param($stmt, "sssi", $name, $slug, $status, $gender_id);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
  $_SESSION['flash_success'] = "Thêm danh mục thành công.";
} else {
  $_SESSION['flash_error'] = "Lỗi thêm danh mục: " . mysqli_error($conn);
}
mysqli_stmt_close($stmt);

header("Location: addcate.php");   // <<< quay về form
exit;
