<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require('./db/connect.php');

require_once './vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$receipt_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($receipt_id <= 0) die('Không tìm thấy phiếu nhập.');

/* Header phiếu + NCC + Người lập */
$sql = "SELECT pr.*, 
               s.supplier_name, s.supplier_phone, s.supplier_email, s.supplier_address,
               a.name AS admin_name
        FROM purchase_receipts pr
        JOIN suppliers s ON s.supplier_id = pr.supplier_id
        LEFT JOIN admin a ON a.id = pr.created_by
        WHERE pr.receipt_id = ?";
$st = $conn->prepare($sql);
$st->bind_param('i', $receipt_id);
$st->execute();
$receipt = $st->get_result()->fetch_assoc();
if (!$receipt) die('Không tìm thấy dữ liệu phiếu nhập.');

/* Items + size */
$sql2 = "SELECT i.product_id, i.size_id, i.quantity, i.unit_price,
                p.name AS product_name,
                ps.size AS size_label
         FROM purchase_receipt_items i
         JOIN product p   ON p.id = i.product_id
         LEFT JOIN product_size ps ON ps.id = i.size_id
         WHERE i.receipt_id = ?
         ORDER BY p.name, ps.size";
$st2 = $conn->prepare($sql2);
$st2->bind_param('i', $receipt_id);
$st2->execute();
$items = $st2->get_result();
$rows  = [];
$total = 0;
while ($r = $items->fetch_assoc()) {
  $r['subtotal'] = $r['quantity'] * $r['unit_price'];
  $total += $r['subtotal'];
  $rows[] = $r;
}

/* Render HTML */
ob_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Phiếu nhập #<?= $receipt_id ?></title>
<style>
  @page { margin: 8mm 11mm 12mm 11mm; }
  body{ font-family: DejaVu Sans, sans-serif; font-size: 15px; color:#000; }

  .store { text-align:left; }
  .store .name{ font-weight:700; letter-spacing:.3px; }
  .store .addr{ font-size:14px; margin-top:2px; }

  .title{ text-align:center; font-weight:700; font-size:18px; margin:0 0 8px; letter-spacing:.2px; }

  .meta{ display:flex; justify-content:flex-end; gap:24px; font-size:12px; margin-bottom:8px; text-align:right; }

  .box{ padding:1px 10px;  }
  .box .line{ margin:2px 0; }

  table{ width:100%; border-collapse:collapse; margin-top:10px; }
  th,td{ border:1px solid #000; padding:6px 6px; }
  th{ text-align:center; }
  td.tl{ text-align:left; } td.tc{ text-align:center; } td.tr{ text-align:right; }

  /* chiều rộng cột */
  col.stt   { width: 7%; }
  col.ten   { width: 47%; }
  col.size  { width: 10%; }
  col.sl    { width: 12%; }
  col.dgia  { width: 12%; }
  col.ttien { width: 12%; }

  .totals{ margin-top:10px; font-size:14px; }

  /* chữ ký */
  .sign { width: 100%; margin-top: 20mm; position: relative; }
  .sign .left, .sign .right{
    width: 45%; text-align: center; font-size: 12px; position: absolute;
  }
  .sign .left{ left:0; }
  .sign .right{ right:0; }
  .sign .hint{ font-style: italic; font-size: 11px; margin-top: 2px; }
  .sign .name{ margin-top: 30mm; font-weight: 700; }
</style>
</head>
<body>

  <!-- Header cửa hàng -->
  <div class="store">
    <div class="name">CỬA HÀNG GIÀY VPACE</div>
    <div class="addr">Địa chỉ: 368A, Đường 3/2, Ninh Kiều, TP. Cần Thơ</div>
    <div class="sdt">SĐT: 0123 456 789</div>
  </div>

  <!-- Tiêu đề -->
  <div class="title">PHIẾU NHẬP HÀNG</div>

  <!-- Số phiếu / Ngày lập -->
  <div class="meta">
    <div><strong>Số phiếu nhập:</strong> #<?= $receipt['receipt_id'] ?></div>
    <div><strong>Ngày lập:</strong> <?= $receipt['receipt_date'] ?></div>
  </div>

  <!-- Thông tin nhà cung cấp -->
  <div class="box">
    <div class="line"><strong>Nhà cung cấp:</strong> <?= htmlspecialchars($receipt['supplier_name']) ?></div>
    <div class="line"><strong>Địa chỉ:</strong> <?= htmlspecialchars($receipt['supplier_address']) ?></div>
    <div class="line">
      <strong>Số điện thoại:</strong> <?= htmlspecialchars($receipt['supplier_phone']) ?>
      &nbsp;&nbsp;&nbsp;
      <strong>Email:</strong> <?= htmlspecialchars($receipt['supplier_email']) ?>
    </div>
  </div>

  <!-- Bảng chi tiết (có cột Size) -->
  <table>
    <colgroup>
      <col class="stt"><col class="ten"><col class="size"><col class="sl"><col class="dgia"><col class="ttien">
    </colgroup>
    <thead>
      <tr>
        <th>STT</th>
        <th>Tên hàng</th>
        <th>Size</th>
        <th>Số lượng</th>
        <th>Đơn giá</th>
        <th>Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      <?php $stt=1; foreach($rows as $r): ?>
      <tr>
        <td class="tc"><?= $stt++ ?></td>
        <td class="tl"><?= htmlspecialchars($r['product_name']) ?></td>
        <td class="tc"><?= htmlspecialchars($r['size_label'] ?? 'FS') ?></td>
        <td class="tr"><?= (int)$r['quantity'] ?></td>
        <td class="tr"><?= number_format($r['unit_price'],0,',','.') ?></td>
        <td class="tr"><?= number_format($r['subtotal'],0,',','.') ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($rows)): ?>
      <tr>
        <td class="tc">1</td>
        <td class="tl">—</td>
        <td class="tc">—</td>
        <td class="tr">0</td>
        <td class="tr">0</td>
        <td class="tr">0</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="totals">
    <strong>Tổng tiền nhập hàng:</strong> <?= number_format($total,0,',','.') ?> VND
  </div>

  <!-- Ký tên -->
  <div class="sign">
    <div class="left">
      <div>Trưởng bộ phận</div>
      <div class="hint">(Ký, ghi rõ họ tên)</div>
    </div>
    <div class="right">
      <div>Lập phiếu nhập</div>
      <div class="hint">(Ký, ghi rõ họ tên)</div>
      <div class="name"><?= htmlspecialchars($receipt['admin_name'] ?? '') ?></div>
    </div>
  </div>

</body>
</html>
<?php
$html = ob_get_clean();

/* Xuất PDF */
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('phieu-nhap-'.$receipt_id.'.pdf', ['Attachment' => false]);
