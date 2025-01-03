<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$id = $_GET['ID'];

$sql = "SELECT * FROM bo_phan WHERE ID = '$id'";
$result = mysqli_query($conn, $sql);
$bo_phan = mysqli_fetch_assoc($result);

?>
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
                        <li class="breadcrumb-item active">Sửa</li>
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
                                    <input value="<?php echo ($bo_phan['Ten']) ?>" type="text" name='ten' required class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mã phòng ban</label>
                                    <input value="<?php echo ($bo_phan['ma_pb']) ?>" type="text" name='ma_pb' required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Tên bộ phận </label>
                                    <input value="<?php echo ($bo_phan['ten_bp']) ?>" type="text" name='ten_bp' required class="form-control">
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <input value="<?php echo ($bo_phan['pass']) ?>" type="password" name='password' required class="form-control">
                                </div>
                            </div> -->
                        </div>
                        <button type="submit" name="btn" class="btn btn-primary">Sửa</button>
                    </div>
                </form>
                <?php
                if (isset($_POST['btn'])) {
                    $ten = $_POST['ten'];
                    $ma_pb = $_POST['ma_pb'];
                    $ten_bp = $_POST['ten_bp'];
                    // $pass = $_POST['password'];
                    $sql = "UPDATE bo_phan SET Ten = '$ten', ma_pb = '$ma_pb', ten_bp = '$ten_bp' WHERE ID = '$id'";
                    $result = mysqli_query($conn, $sql);
                    // $sql = "UPDATE users SET password = '$pass' WHERE username = '$email'";
                    // $result = mysqli_query($conn, $sql);
                    if ($result == true) {
                        echo "Sửa phòng ban thành công <a href='ql_bo_phan.php'>Danh sách</a>";
                    }
                }
                ?>
            </div>
    </section>
</div>
<?php require 'footer.php' ?>