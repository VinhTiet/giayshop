<?php
    require('./modules/header.php');

?>

<div>
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">

                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Thêm Danh Mục Tin Tức</h1>
                            </div>
                            <form class="user" method="post" action="news.php">

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="name" name="name"
                                        placeholder="Tên Danh Mục">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="status" name="status"
                                        placeholder="Trạng Thái">
                                </div>

                                
                                <button class="btn btn-primary">Thêm Mới</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
     require('./modules/footer.php');
?>