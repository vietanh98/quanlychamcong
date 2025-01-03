<?php require 'table.html' ?>
<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$nhan_vien = $conn->query("SELECT * FROM nhan_vien");  ?>
<div class="content-wrapper" style="min-height: 353px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Ứng lương</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Ứng lương</a></li>
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
                    <h3 class="card-title">Thêm Ứng lương</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nhân viên</label>
                                    <select name="manv" required class="form-control">
                                        <option value="">-----Chọn nhân viên-----</option>
                                        <?php while ($row = $nhan_vien->fetch_assoc()) : ?>
                                            <option value="<?php echo $row['Ma_nv'] ?>"><?php echo $row['Hoten'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Số tiền</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name='sotien' class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Vnđ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ngày thực hiện</label>
                                    <input type="date" name="ngay_thuc_hien" required class="form-control" />
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                    </form>
                </div>
            </div>
            <?php
            if (isset($_POST['btn'])) {
                $ma = $_POST['manv'];
                $sotien = $_POST['sotien'];
                $ngay = $_POST['ngay_thuc_hien'];
                $kq = " select * from ung_luong where Ma_nv='$ma'";
                $kq_con = mysqli_query($conn, $kq);
                $sql = "INSERT INTO ung_luong VALUES(ID,'$ma','$sotien','$ngay')";
                $result = mysqli_query($conn, $sql);
                if ($result == true) {
                    echo "Thêm Thành Công !Hãy vào <a href='ql_ung_luong.php'>Danh sách </a> để xem lại";
                }
            }
            ?>
    </section>
</div>
<?php require 'footer.php' ?>