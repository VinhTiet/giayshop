<?php
session_start();
require('./modules/header.php');
require("./db/connect.php");

/* Tổng hợp tồn kho theo size:
   stock_calc = qty_in - qty_out (Delivered) */
$sql = "
SELECT 
    p.id  AS product_id,
    p.name AS product_name,
    ps.id AS size_id,
    ps.size,
    COALESCE(inq.qty_in, 0)  AS qty_in,
    COALESCE(outq.qty_out,0) AS qty_out,
    COALESCE(inq.qty_in, 0) - COALESCE(outq.qty_out,0) AS stock_calc
FROM product_size ps
JOIN product p ON p.id = ps.product_id
LEFT JOIN (
    SELECT size_id, SUM(quantity) AS qty_in
    FROM purchase_receipt_items
    GROUP BY size_id
) inq ON inq.size_id = ps.id
LEFT JOIN (
    SELECT od.size_id, SUM(od.num) AS qty_out
    FROM order_details od
    JOIN orders o ON o.id = od.order_id AND o.status = 'Delivered'
    GROUP BY od.size_id
) outq ON outq.size_id = ps.id
ORDER BY p.name, ps.size
";
$result = $conn->query($sql);

/* Gom theo sản phẩm để render */
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['product_name']][] = [
        'size_id'   => (int)$row['size_id'],
        'size'      => $row['size'],
        'qty_in'    => (int)$row['qty_in'],
        'qty_out'   => (int)$row['qty_out'],
        'stock'     => (int)$row['stock_calc'],
    ];
}
mysqli_close($conn);
?>
<head>
    <title>Quản lý tồn kho</title>
    <style>
      body{font-family:Arial, sans-serif;background:#f9f9f9;margin:0}
      h1{margin:20px 0;text-align:center;color:#333}
      .container{width:90%;margin:auto}
      table{width:100%;border-collapse:collapse;background:#fff;margin:20px 0;
            box-shadow:0 2px 10px rgba(0,0,0,.1)}
      th,td{padding:10px 15px;border:1px solid #ddd;text-align:left}
      th{background:#f1f1f1;color:#000}
      .product-header{background:#0d6efd;font-weight:bold}
      .product-header td{color:#fff}
      .no-data{text-align:center;font-style:italic;color:#666}
      .num{text-align:right}
      .muted{color:#777;font-size:12px}
    </style>
</head>
<body>
  <div class="container">
    <h1>Quản lý tồn kho</h1>

    <?php if (empty($products)): ?>
      <p class="no-data">Hiện tại chưa có sản phẩm/size nào.</p>
    <?php else: ?>
      <table>
        <?php foreach ($products as $product_name => $sizes): ?>
          <tr class="product-header">
            <td colspan="5"><?= htmlspecialchars($product_name) ?></td>
          </tr>
          <tr>
            <th>Size</th>
            <th class="num">Nhập (từ phiếu)</th>
            <th class="num">Đã giao</th>
            <th class="num">Tồn hiện tại</th>
            <th>Ghi chú</th>
          </tr>
          <?php foreach ($sizes as $s): ?>
            <tr>
              <td><?= htmlspecialchars($s['size']) ?></td>
              <td class="num"><?= number_format($s['qty_in']) ?></td>
              <td class="num"><?= number_format($s['qty_out']) ?></td>
              <td class="num"><strong><?= number_format($s['stock']) ?></strong></td>
              
            </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </table>
      
    <?php endif; ?>
  </div>
</body>

<?php require('./modules/footer.php'); ?>
