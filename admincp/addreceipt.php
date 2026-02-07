<?php
// admincp/addreceipt.php
if (session_status() === PHP_SESSION_NONE) session_start();
require('./modules/header.php');
require('./db/connect.php');

/* Lấy ID admin từ session (hỗ trợ cả kiểu cũ và mới) */
$ADMIN_ID = $_SESSION['admin_id'] ?? ($_SESSION['admin']['id'] ?? null);
if (!$ADMIN_ID) { header('Location: loginform.php'); exit; }

/* Lấy danh sách NCC & SP cho dropdown */
$suppliers = mysqli_query($conn, "SELECT supplier_id, supplier_name FROM suppliers ORDER BY supplier_name");
$products  = mysqli_query($conn, "SELECT id, name FROM product ORDER BY name");

/* Lấy toàn bộ size theo sản phẩm để dùng client-side */
$sqlSizes = "SELECT id, product_id, COALESCE(size,'') AS size FROM product_size ORDER BY product_id, size";
$rsSizes  = mysqli_query($conn, $sqlSizes);
$sizesByProduct = [];
while ($row = mysqli_fetch_assoc($rsSizes)) {
  $pid = (int)$row['product_id'];
  if (!isset($sizesByProduct[$pid])) $sizesByProduct[$pid] = [];
  $sizesByProduct[$pid][] = [
    'id'   => (int)$row['id'],
    'size' => (string)$row['size']
  ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = (int)($_POST['supplier_id'] ?? 0);
    $receipt_date = !empty($_POST['receipt_date']) ? $_POST['receipt_date'] : date('Y-m-d H:i:s');
    $note = trim($_POST['note'] ?? '');

    $pids    = $_POST['product_id'] ?? [];
    $sizeids = $_POST['size_id'] ?? [];
    $qtys    = $_POST['quantity'] ?? [];
    $prices  = $_POST['unit_price'] ?? [];

    $items = [];
    for ($i=0; $i<count($pids); $i++) {
      $pid = (int)($pids[$i] ?? 0);
      $sid = (int)($sizeids[$i] ?? 0);
      $q   = (int)($qtys[$i] ?? 0);
      $pr  = (float)($prices[$i] ?? 0);
      if ($pid>0 && $sid>0 && $q>0 && $pr>=0) {
        $items[] = [$pid, $sid, $q, $pr];
      }
    }

    if ($supplier_id<=0 || empty($items)) {
      echo "<div class='alert alert-danger'>Vui lòng chọn nhà cung cấp và thêm ít nhất 1 dòng hợp lệ (đã chọn sản phẩm + size + số lượng).</div>";
    } else {
      $total = 0; foreach($items as $it){ $total += $it[2]*$it[3]; }

      mysqli_begin_transaction($conn);
      try {
        // INSERT phiếu nhập
        $sql = "INSERT INTO purchase_receipts (supplier_id, created_by, receipt_date, total_amount, note)
                VALUES (?, ?, ?, ?, ?)";
        $st  = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($st, "iisis", $supplier_id, $ADMIN_ID, $receipt_date, $total, $note);
        if (!mysqli_stmt_execute($st)) throw new Exception(mysqli_error($conn));
        $rid = mysqli_insert_id($conn);

        // INSERT chi tiết + cập nhật tồn kho
        $sti = mysqli_prepare($conn, "INSERT INTO purchase_receipt_items
                    (receipt_id, product_id, size_id, quantity, unit_price)
                    VALUES (?,?,?,?,?)");
        $stu = mysqli_prepare($conn, "UPDATE product_size SET stock = stock + ? WHERE id = ?");

        foreach ($items as $it){
          // $it = [product_id, size_id, qty, price]
          mysqli_stmt_bind_param($sti, "iiiid", $rid, $it[0], $it[1], $it[2], $it[3]);
          if (!mysqli_stmt_execute($sti)) throw new Exception(mysqli_error($conn));

          mysqli_stmt_bind_param($stu, "ii", $it[2], $it[1]);
          if (!mysqli_stmt_execute($stu)) throw new Exception(mysqli_error($conn));
        }

        mysqli_commit($conn);
        echo "<script>
          alert('Tạo phiếu nhập #{$rid} thành công!');
          window.location='viewreceipt.php?id={$rid}';
        </script>";
      } catch (Exception $e){
        mysqli_rollback($conn);
        echo "<div class='alert alert-danger'>Lỗi lưu phiếu: ".htmlspecialchars($e->getMessage())."</div>";
      }
    }
}
?>

<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Tạo Phiếu Nhập</h6>
    <a href="listreceipts.php" class="btn btn-secondary btn-sm">Danh sách phiếu nhập</a>
  </div>

  <div class="card-body">
    <form method="POST" id="receiptForm">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label>Nhà Cung Cấp</label>
            <select name="supplier_id" class="form-control" required>
              <option value="">-- Chọn nhà cung cấp --</option>
              <?php while ($s = mysqli_fetch_assoc($suppliers)) : ?>
                <option value="<?= $s['supplier_id'] ?>"><?= htmlspecialchars($s['supplier_name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Ngày Nhập</label>
            <input type="datetime-local" name="receipt_date" class="form-control" value="<?= date('Y-m-d\TH:i') ?>">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Ghi chú</label>
            <input type="text" name="note" class="form-control" placeholder="Ghi chú (nếu có)">
          </div>
        </div>
      </div>

      <hr>

      <div class="table-responsive">
        <table class="table table-bordered" id="itemsTable">
          <thead>
            <tr>
              <th style="width:30%">Sản phẩm</th>
              <th style="width:20%">Size</th>
              <th style="width:15%">Số lượng</th>
              <th style="width:15%">Đơn giá</th>
              <th style="width:15%">Thành tiền</th>
              <th style="width:5%"></th>
            </tr>
          </thead>
          <tbody id="itemsBody">
            <tr>
              <td>
                <select name="product_id[]" class="form-control product-select" required>
                  <option value="">-- Chọn sản phẩm --</option>
                  <?php mysqli_data_seek($products, 0); while ($p = mysqli_fetch_assoc($products)) : ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                  <?php endwhile; ?>
                </select>
              </td>
              <td>
                <select name="size_id[]" class="form-control size-select" required disabled>
                  <option value="">-- Chọn size --</option>
                </select>
                <!-- NÚT TẠO DÒNG CHO TẤT CẢ SIZE -->
                <button type="button" class="btn btn-link btn-sm p-0 mt-1 gen-all-sizes">
                  + Tạo dòng cho tất cả size
                </button>
              </td>
              <td><input type="number" name="quantity[]" class="form-control qty" min="1" value="1" required></td>
              <td><input type="number" name="unit_price[]" class="form-control price" min="0" step="0.01" value="0"></td>
              <td class="line-total">0</td>
              <td><button type="button" class="btn btn-sm btn-danger remove-row">&times;</button></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6">
                <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">+ Thêm dòng</button>
              </td>
            </tr>
            <tr>
              <th colspan="4" class="text-right">Tổng tiền:</th>
              <th id="grandTotal">0</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

      <button type="submit" class="btn btn-primary">Lưu Phiếu Nhập</button>
      <a href="listreceipts.php" class="btn btn-secondary">Hủy</a>
    </form>
  </div>
</div>

<script>
  document.addEventListener('change', function(e){
  if (e.target.classList.contains('product-select')) {
    const tr = e.target.closest('tr');
    const sizeSelect = tr.querySelector('.size-select');
    const pid = e.target.value ? parseInt(e.target.value) : 0;

    // 1) Đổ size theo sản phẩm
    fillSizeOptions(sizeSelect, pid);

    // 2) Lấy giá nhập gợi ý
    const priceInput = tr.querySelector('.price');
    if (!pid) {
      if (priceInput) priceInput.value = 0;
      recalcRow(tr);
      recalcGrand();
      return;
    }

    fetch('get_product_price.php?id=' + pid)
      .then(res => res.json())
      .then(data => {
        if (data.ok) {
          // Gợi ý giá nhập
          if (priceInput) {
            priceInput.value = data.import_price > 0 ? data.import_price : 0;
          }
          recalcRow(tr);
          recalcGrand();
        } else {
          alert(data.message || 'Không lấy được giá sản phẩm');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Lỗi khi lấy giá nhập');
      });
  }
});

// Data size theo product từ PHP
const sizesByProduct = <?= json_encode($sizesByProduct, JSON_UNESCAPED_UNICODE) ?>;

// clone <option> sản phẩm để dùng khi thêm dòng
const productOptionsHtml = `<?php
  mysqli_data_seek($products, 0);
  $opts = '<option value=\"\">-- Chọn sản phẩm --</option>';
  while ($p = mysqli_fetch_assoc($products)) {
      $opts .= '<option value=\"'.$p['id'].'\">'.htmlspecialchars($p['name'], ENT_QUOTES).'</option>';
  }
  echo $opts;
?>`;

function fillSizeOptions(selectEl, productId){
  selectEl.innerHTML = '<option value="">-- Chọn size --</option>';
  if (!productId || !sizesByProduct[productId]) {
    selectEl.disabled = true;
    return;
  }
  sizesByProduct[productId].forEach(sz => {
    const opt = document.createElement('option');
    opt.value = sz.id;
    opt.textContent = sz.size || '(free size)';
    selectEl.appendChild(opt);
  });
  selectEl.disabled = false;
}

function recalcRow(tr){
  const qty   = parseFloat(tr.querySelector('.qty').value || 0);
  const price = parseFloat(tr.querySelector('.price').value || 0);
  const total = qty * price;
  tr.querySelector('.line-total').innerText = total.toFixed(2);
}

function recalcGrand(){
  let s = 0;
  document.querySelectorAll('#itemsBody .line-total').forEach(td => {
    s += parseFloat(td.innerText || 0);
  });
  document.getElementById('grandTotal').innerText = s.toFixed(2);
}

// Thêm dòng mới (dạng bình thường)
document.getElementById('addRow').addEventListener('click', () => {
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>
      <select name="product_id[]" class="form-control product-select" required>
        ${productOptionsHtml}
      </select>
    </td>
    <td>
      <select name="size_id[]" class="form-control size-select" required disabled>
        <option value="">-- Chọn size --</option>
      </select>
      <button type="button" class="btn btn-link btn-sm p-0 mt-1 gen-all-sizes">
        + Tạo dòng cho tất cả size
      </button>
    </td>
    <td><input type="number" name="quantity[]" class="form-control qty" min="1" value="1" required></td>
    <td><input type="number" name="unit_price[]" class="form-control price" min="0" step="0.01" value="0"></td>
    <td class="line-total">0</td>
    <td><button type="button" class="btn btn-sm btn-danger remove-row">&times;</button></td>
  `;
  document.getElementById('itemsBody').appendChild(tr);
});

// Khi đổi sản phẩm → load size
document.addEventListener('change', function(e){
  if (e.target.classList.contains('product-select')) {
    const tr = e.target.closest('tr');
    const sizeSelect = tr.querySelector('.size-select');
    const pid = e.target.value ? parseInt(e.target.value) : 0;
    fillSizeOptions(sizeSelect, pid);
  }
});

// Tính tiền
document.addEventListener('input', function(e){
  if(e.target.classList.contains('qty') || e.target.classList.contains('price')){
    const tr = e.target.closest('tr');
    recalcRow(tr); recalcGrand();
  }
});

// Xóa dòng
document.addEventListener('click', function(e){
  if(e.target.classList.contains('remove-row')){
    const tr = e.target.closest('tr');
    tr.parentNode.removeChild(tr);
    recalcGrand();
  }
});

// *** NÚT "TẠO DÒNG CHO TẤT CẢ SIZE" ***
document.addEventListener('click', function(e){
  if (e.target.classList.contains('gen-all-sizes')) {
    const tr = e.target.closest('tr');
    const productSel = tr.querySelector('.product-select');
    const pid = parseInt(productSel.value || 0);
    if (!pid) {
      alert('Vui lòng chọn sản phẩm trước.');
      return;
    }

    const sizes = sizesByProduct[pid] || [];
    if (!sizes.length) {
      alert('Sản phẩm này chưa có size trong bảng product_size.');
      return;
    }

    const baseQty   = parseInt(tr.querySelector('.qty').value || 1);
    const basePrice = parseFloat(tr.querySelector('.price').value || 0);

    // Xóa dòng mẫu (dòng đang bấm nút)
    tr.remove();

    // Tạo 1 dòng cho MỖI size
    sizes.forEach(sz => {
      const newTr = document.createElement('tr');
      newTr.innerHTML = `
        <td>
          <select name="product_id[]" class="form-control product-select" required>
            ${productOptionsHtml}
          </select>
        </td>
        <td>
          <select name="size_id[]" class="form-control size-select" required>
            <option value="${sz.id}">${sz.size || '(free size)'}</option>
          </select>
        </td>
        <td><input type="number" name="quantity[]" class="form-control qty" min="1" value="${baseQty}" required></td>
        <td><input type="number" name="unit_price[]" class="form-control price" min="0" step="0.01" value="${basePrice.toFixed(2)}"></td>
        <td class="line-total">0</td>
        <td><button type="button" class="btn btn-sm btn-danger remove-row">&times;</button></td>
      `;
      document.getElementById('itemsBody').appendChild(newTr);

      // set sản phẩm cho dòng mới
      const sel = newTr.querySelector('.product-select');
      sel.value = String(pid);

      // tính thành tiền cho dòng mới
      recalcRow(newTr);
    });

    recalcGrand();
  }
});
</script>

<?php require('./modules/footer.php'); ?>
