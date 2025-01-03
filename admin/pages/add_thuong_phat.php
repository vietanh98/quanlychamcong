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
                    <h1 class="m-0 text-dark">Thưởng/Phạt</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Thưởng/Phạt</a></li>
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
                    <h3 class="card-title">Thêm Thưởng/Phạt</h3>

                </div>
                <form method="post">
                    <div class="card-body">
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
                                    <label>Loại hình</label>
                                    <select name="loaihinh" class="form-control" required>
                                        <option value="Thưởng">Thưởng</option>
                                        <option value="Phạt">Phạt</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Số tiền</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name='sotien' required class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Vnđ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ngày thực hiện</label>
                                    <input type="date" name="ngay_thuc_hien" required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Lí do</label>
                                    <input type="text" name="lido" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
            <?php
            if (isset($_POST['btn'])) {
                $ma = $_POST['manv'];
                $loaihinh = $_POST['loaihinh'];
                $sotien = $_POST['sotien'];
                $lido = $_POST['lido'];
                $ngay = $_POST['ngay_thuc_hien'];
                $kq = " select * from thuong_phat where Ma_nv='$ma'";
                $kq_con = mysqli_query($conn, $kq);
                $sql = "INSERT INTO thuong_phat VALUES(ID_thuong_phat,'$ma','$loaihinh','$sotien',' $lido','$ngay')";
                $result = mysqli_query($conn, $sql);
                if ($result == true) {
                    echo "Thêm Thành Công !Hãy vào <a href='ql_thuong_phat.php'>Danh sách </a> để xem lại";
                }
            }
            ?>
    </section>
</div>
<?php require 'footer_ql.php' ?>