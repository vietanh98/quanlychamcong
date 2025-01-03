<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan"); ?>
<div class="content-wrapper" style="min-height: 353px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Phòng ban</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Phòng ban</a></li>
                        <li class="breadcrumb-item active">Thêm</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Quản lý phòng ban</h3>
                </div>
                <form method="post">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Tên phòng ban</label>
                                    <input type="text" name='ten' required class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mã phòng ban</label>
                                    <input type="text" name='ma_pb' required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Bộ phận</label>
                                    <input type="text" name='ten_bp' required class="form-control">
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <input type="password" name='password' required class="form-control">
                                </div>
                            </div> -->
                        </div>

                        <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
                <?php
                if (isset($_POST['btn'])) {
                    $ten = $_POST['ten'];
                    $ma_pb = $_POST['ma_pb'];
                    $ten_bp = $_POST['ten_bp'];
                    $sql = " select * from bo_phan where Ten = '$ten'";
                    $result = mysqli_query($conn, $sql);
                    $dem = mysqli_num_rows($result);
                    if ($dem > 0) {
                        echo "Tồn tại";
                        exit();
                    } else {
                        $sql = "INSERT INTO bo_phan(Ten, ma_pb, ten_bp) VALUES('$ten', '$ma_pb','$ten_bp')";
                        $result = mysqli_query($conn, $sql);
                        // $sql1 = "INSERT INTO users(username, password, role) VALUES('$email','$pass',1)";
                        // $result1 = mysqli_query($conn, $sql1);
                        if ($result == true) {
                            echo "Thêm phòng ban thành công <a href='ql_bo_phan.php'>Danh sách</a>";
                        }
                    }
                }
                ?>
            </div>
    </section>
</div>
<?php require 'footer.php' ?>