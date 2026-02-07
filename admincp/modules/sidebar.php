<!-- Sidebar -->
<ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">
    <div class="header__logo">
        <a href="index.php"><img src="./assets/img/logochucam.png" alt=""></a>
    </div>
    <!-- Sidebar - Brand -->


    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Bảng Điều Khiển</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Chức Năng Chính
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="revenue.php" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-file-pdf"></i>
            <span>Thống Kê Doanh Thu</span>
        </a>

    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="inventory.php" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-file-pdf"></i>
            <span>Quản Lý Tồn Kho</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true"
            aria-controls="collapseThree">
            <i class="fas fa-tags"></i>
            <span>Thương Hiệu</span>
        </a>
        <div id="collapseFive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tùy Chọn</h6>
                <a class="collapse-item" href="./listbrands.php">Liệt Kê</a>
                <a class="collapse-item" href="./addbrands.php">Thêm Mới</a>
            </div>
        </div>
    </li>
    

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-calendar-week"></i>
            <span>Danh Mục Sản Phẩm</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tùy Chọn</h6>
                <a class="collapse-item" href="./listcate.php">Liệt Kê</a>
                <a class="collapse-item" href="./addcate.php">Thêm Mới</a>
            </div>
        </div>
    </li>


    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true"
            aria-controls="collapseThree">
            <i class="fas fa-guitar"></i>
            <span>Sản Phẩm</span>
        </a>
        <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tùy Chọn</h6>
                <a class="collapse-item" href="./listproducts.php">Liệt Kê</a>
                <a class="collapse-item" href="addproducts.php">Thêm Mới</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecatenews"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-calendar-week"></i>
            <span>Danh Mục Tin Tức</span>
        </a>
        <div id="collapsecatenews" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tùy Chọn</h6>
                <a class="collapse-item" href="./listnewscate.php">Liệt Kê</a>
                <a class="collapse-item" href="./addnewscate.php">Thêm Mới</a>
            </div>
        </div>
    </li>


    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsenews" aria-expanded="true"
            aria-controls="collapseThree">
            <i class="fas fa-guitar"></i>
            <span>Tin Tức</span>
        </a>
        <div id="collapsenews" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tùy Chọn</h6>
                <a class="collapse-item" href="./listnews.php">Liệt Kê</a>
                <a class="collapse-item" href="./addnews.php">Thêm Mới</a>
            </div>
        </div>
    </li>

<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProcure" aria-expanded="false" aria-controls="collapseProcure">
    <i class="fas fa-truck-loading"></i>
    <span>Nhập Hàng</span>
  </a>
  <div id="collapseProcure" class="collapse" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <h6 class="collapse-header">Nhà Cung Cấp</h6>
      <a class="collapse-item" href="listsupplier.php">Liệt Kê</a>
      <a class="collapse-item" href="addsupplier.php">Thêm Mới</a>
      <div class="dropdown-divider"></div>
      <h6 class="collapse-header">Phiếu Nhập</h6>
      <a class="collapse-item" href="listreceipts.php">Liệt Kê</a>
      <a class="collapse-item" href="addreceipt.php">Tạo Phiếu</a>
    </div>
  </div>
</li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-shopping-cart"></i>
            <span>Đơn Hàng</span>
        </a>
        <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tùy Chọn</h6>
                <a class="collapse-item" href="listorders.php">Liệt Kê</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsefive" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-reply-all"></i>
            <span>Phản Hồi</span>
        </a>
        <div id="collapsefive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tùy Chọn</h6>
                <a class="collapse-item" href="feedback.php">Liệt Kê</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-user"></i>
            <span>Người Dùng</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tùy Chọn</h6>
                <a class="collapse-item" href="listuser.php">Liệt Kê</a>
                <a class="collapse-item" href="adduser.php">Thêm Mới</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <!-- <hr class="sidebar-divider"> -->

    <!-- Heading -->
    <!-- <div class="sidebar-heading">
        Addons
    </div> -->

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Trang</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="loginform.php">Đăng Nhập</a>
                <a class="collapse-item" href="register.php">Đăng Kí</a>
                <a class="collapse-item" href="forgotpw.php">Quên MậT Khẩu</a>

            </div>
        </div>
    </li> -->
<!-- NHẬP HÀNG -->


<!-- KHO -->
<!-- <li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseWarehouse" aria-expanded="false" aria-controls="collapseWarehouse">
    <i class="fas fa-warehouse"></i>
    <span>Kho</span>
  </a>
  <div id="collapseWarehouse" class="collapse" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <h6 class="collapse-header">Kiểm Kê</h6>
      <a class="collapse-item" href="stocktake_sessions.php">Phiên Kiểm Kê</a>
      <a class="collapse-item" href="stocktake_start.php">Mở Phiên</a>
      <div class="dropdown-divider"></div>
      <h6 class="collapse-header">Điều Chỉnh</h6>
      <a class="collapse-item" href="adjustments.php">Liệt Kê</a>
      <a class="collapse-item" href="add_adjustment.php">Tạo Điều Chỉnh</a>
    </div>
  </div>
</li>

<!-- GIAO HÀNG -->
<!-- <li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseShip" aria-expanded="false" aria-controls="collapseShip">
    <i class="fas fa-shipping-fast"></i>
    <span>Giao Hàng</span>
  </a>
  <div id="collapseShip" class="collapse" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item" href="shipments.php">Vận Đơn</a>
      <a class="collapse-item" href="carriers.php">Đối Tác VC</a>
    </div>
  </div>
</li> -->

<!-- ĐỔI / TRẢ -->
<!-- <li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReturn" aria-expanded="false" aria-controls="collapseReturn">
    <i class="fas fa-undo-alt"></i>
    <span>Đổi/Trả</span>
  </a>
  <div id="collapseReturn" class="collapse" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item" href="returns.php">Liệt Kê</a>
      <a class="collapse-item" href="add_return.php">Tạo Phiếu Trả</a>
    </div>
  </div>
</li> -->

<!-- TÀI CHÍNH -->
<!-- <li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinance" aria-expanded="false" aria-controls="collapseFinance">
    <i class="fas fa-file-invoice-dollar"></i>
    <span>Tài Chính</span>
  </a>
  <div id="collapseFinance" class="collapse" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item" href="supplier_payments.php">Thanh Toán NCC</a>
      <a class="collapse-item" href="cod_reconciliation.php">Đối Soát COD</a>
    </div>
  </div>
</li> -->

<!-- PHÂN QUYỀN -->
<!-- <li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAcl" aria-expanded="false" aria-controls="collapseAcl">
    <i class="fas fa-user-shield"></i>
    <span>Phân Quyền</span>
  </a>
  <div id="collapseAcl" class="collapse" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item" href="roles.php">Vai Trò</a>
      <a class="collapse-item" href="permissions.php">Quyền</a>
      <a class="collapse-item" href="audit_logs.php">Nhật Ký</a>
    </div>
  </div>
</li> --> -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->

</ul>
<!-- End of Sidebar -->