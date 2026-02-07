<?php require('./modules/header.php'); ?>

<div>
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <div class="row">
          <div class="col-lg-12">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Thêm Sản Phẩm</h1>
              </div>

              <!-- VẪN POST TỚI products.php -->
              <form class="user" method="post" action="products.php" enctype="multipart/form-data" id="productForm">

                <!-- Tên, tóm tắt, mô tả -->
                <div class="form-group">
                  <label class="form-label">Tên Sản Phẩm</label>
                  <input type="text" class="form-control form-control-user" id="name" name="name"
                         placeholder="Tên Sản Phẩm" required>
                </div>

                <div class="form-group">
                  <label class="form-label">Tóm Tắt Sản Phẩm</label>
                  <textarea name="summary" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Mô Tả Sản Phẩm</label>
                  <textarea name="description" class="form-control" rows="6"></textarea>
                </div>

                <!-- Giá -->
                <div class="form-group row">
                  <div class="col-sm-4 mb-sm-0">
                    <label class="form-label">Giá Gốc</label>
                    <input type="number" class="form-control form-control-user" name="price" id="price"
                           placeholder="Giá Gốc" min="0" required>
                  </div>
                  <div class="col-sm-4 mb-sm-0">
                    <label class="form-label">Giá Khuyến Mãi (nếu có)</label>
                    <input type="number" class="form-control form-control-user" name="discount" id="discount"
                           placeholder="Giá Khuyến Mãi" min="0">
                  </div>
                </div>

                <!-- Ảnh: cho phép chọn nhiều | KHỚP TÊN 'thumbnail[]' VỚI products.php -->
                <div class="form-group">
                  <label class="form-label">Ảnh Sản Phẩm (nhiều ảnh)</label>
                  <input type="file" name="thumbnail[]" id="images" class="form-control"
                         accept=".jpg,.jpeg,.png,.webp" multiple>
                  <small class="text-muted d-block mt-1">Bạn có thể chọn nhiều ảnh (jpg, png, webp).
                    Ảnh đầu tiên sẽ là ảnh đại diện.</small>
                  <div id="preview" class="mt-2" style="display:flex;gap:10px;flex-wrap:wrap;"></div>
                </div>

                <!-- Danh mục, Thương hiệu -->
                <div class="form-group">
                  <label class="form-label">Danh Mục</label>
                  <select class="form-control" name="category" id="category" required>
                    <option value="">Chọn Danh Mục</option>
                    <?php
                      require("./db/connect.php");
                      $rs1 = mysqli_query($conn, "SELECT id,name FROM category ORDER BY name");
                      while ($row = mysqli_fetch_assoc($rs1)) { ?>
                        <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Thương Hiệu</label>
                  <select class="form-control" name="brand" id="brand" required>
                    <option value="">Chọn Thương Hiệu</option>
                    <?php
                      $rs2 = mysqli_query($conn, "SELECT id,name FROM brand ORDER BY name");
                      while ($row = mysqli_fetch_assoc($rs2)) { ?>
                        <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <!-- BỘ SIZE MẶC ĐỊNH + TỰ NHẬP SIZE -->
                <div class="form-group">
                  <label class="form-label">Chọn size</label>
                  <select name="size_preset" class="form-control" id="size_preset">
                    <option value="">— Không tạo size —</option>
                    <option value="VN_39_45">39–45 (nam)</option>
                    <option value="NU_35_42">35–42 (nữ)</option>
                    <option value="ONE">Một size (FS)</option>
                  </select>
                  <!-- <small class="text-muted">Khi lưu, hệ thống sẽ khởi tạo size với <b>stock = 0</b>.
                    Tồn kho sẽ cập nhật qua Phiếu nhập.</small> -->
                </div>

                <!-- <div class="form-group">
                  <label class="form-label">Tự nhập size (tùy chọn)</label>
                  <input type="text" id="custom_sizes" class="form-control"
                         placeholder="Ví dụ: 35,36,37,38 hoặc S,M,L,XL">
                  <small class="text-muted">Nếu nhập, danh sách này sẽ được dùng (ưu tiên hơn preset).</small>
                </div> -->

                <!-- CHỖ SINH INPUT ẨN sizes[] & size_stock[] ĐỂ products.php NHẬN -->
                <div id="generated-sizes"></div>

                <!-- Nếu muốn vẫn hỗ trợ số lượng tổng (không size) -->
                <!-- <div class="form-group">
                  <label class="form-label">Số lượng tổng (không có size) — tùy chọn</label>
                  <input type="number" name="general_stock" class="form-control" placeholder="Nhập số lượng tổng" min="0">
                </div> -->

                <button class="btn btn-primary">Thêm Mới</button>
              </form>

              <script>
              // Xem trước ảnh
              document.getElementById('images').addEventListener('change', function(e) {
                const preview = document.getElementById('preview');
                preview.innerHTML = '';
                const files = Array.from(e.target.files);
                files.forEach(file => {
                  const url = URL.createObjectURL(file);
                  const img = document.createElement('img');
                  img.src = url;
                  img.style.width = '90px';
                  img.style.height = '90px';
                  img.style.objectFit = 'cover';
                  img.style.border = '1px solid #ddd';
                  img.style.borderRadius = '4px';
                  preview.appendChild(img);
                });
              });

              // Tạo các input ẩn sizes[] & size_stock[] trước khi submit
              document.getElementById('productForm').addEventListener('submit', function(e) {
                const preset = document.getElementById('size_preset').value.trim();
                const custom = document.getElementById('custom_sizes').value.trim();
                const holder = document.getElementById('generated-sizes');
                holder.innerHTML = ''; // clear cũ

                // Ưu tiên custom nếu có
                let sizeList = [];
                if (custom.length > 0) {
                  sizeList = custom.split(',').map(s => s.trim()).filter(s => s.length > 0);
                } else {
                  // Dùng preset
                  if (preset === 'VN_39_45') {
                    for (let s = 39; s <= 45; s++) sizeList.push(String(s));
                  } else if (preset === 'NU_35_42') {
                    for (let s = 35; s <= 42; s++) sizeList.push(String(s));
                  } else if (preset === 'ONE') {
                    sizeList.push('FS'); // Free Size
                  }
                }

                // Deduplicate
                sizeList = Array.from(new Set(sizeList));

                // Sinh input ẩn tương thích với products.php (sizes[] & size_stock[])
                if (sizeList.length > 0) {
                  sizeList.forEach(sz => {
                    const i1 = document.createElement('input');
                    i1.type = 'hidden';
                    i1.name = 'sizes[]';
                    i1.value = sz;
                    holder.appendChild(i1);

                    const i2 = document.createElement('input');
                    i2.type = 'hidden';
                    i2.name = 'size_stock[]';
                    i2.value = 0; // mặc định 0; sẽ nhập kho qua phiếu nhập
                    holder.appendChild(i2);
                  });
                }
              });
              </script>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require('./modules/footer.php'); ?>
