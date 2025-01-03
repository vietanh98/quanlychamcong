<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$nhan_vien = $conn->query("SELECT * FROM nhan_vien");
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$he_so_luong = $conn->query("SELECT * FROM luong"); ?>
<?php
$manv = $_GET['Ma_nv'];
$sql = "SELECT nhan_vien.*,bo_phan.Ten,luong.Luong_co_ban
        FROM nhan_vien JOIN bo_phan ON nhan_vien.ID_bophan = bo_phan.ID 
        JOIN luong ON nhan_vien.He_so_luong = luong.He_so_luong  
        where nhan_vien.Ma_nv ='$manv'";
$result = mysqli_query($conn, $sql) or die("Có lỗi xảy ra: " . mysqli_error($conn));
while ($row = mysqli_fetch_assoc($result)) {
    $ten = $row['Hoten'];
    $gt = $row['Gioitinh'];
    $chucdanh = $row['chucdanh'];
    // $sdt = $row['SDT'];
    $email = $row['username'];
    $pass = $row['pass'];
    $bophan = $row['ID_bophan'];
    $tenbophan = $row['Ten'];
    $mabo = $row['Ma_bo'];
    $manhom = $row['Ma_nhom'];
    // $end = $row['Gio_ket_thuc'];
    // $ngaylam = $row['Ngaylamviec'];
    $luong = $row['Luong_co_ban'];
    $hsluong = $row['He_so_luong'];
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Thông tin nhân viên</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="ql_nhan_vien.php">Nhân viên</a></li>
                        <li class="breadcrumb-item active"><?php echo $ten ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mã nhân viên</label>
                                            <input type="text" name="manv" class="form-control" readonly='true'
                                                value="<?php echo $manv ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Họ và tên</label>
                                            <input type="text" name="ten" class="form-control"
                                                value="<?php echo $ten ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Giới tính</label><br>
                                            <input type="text" name="gioitinh" class="form-control" readonly='true'
                                                value="<?php echo $gt ?>">

                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Phòng ban</label>
                                            <select name="bophan" id="bophan" value="<?php echo $bophan ?>"
                                                class="form-control">
                                                <option value="<?php echo $bophan ?>"><?php echo $tenbophan ?></option>
                                                <?php while ($row = $bo_phan->fetch_assoc()) : ?>
                                                    <option value="<?php echo $row['ID'] ?>"><?php echo $row['Ten'] ?>
                                                    </option>
                                                <?php endwhile ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Chức danh</label>
                                            <input type="text" name="chucdanh" class="form-control"
                                                placeholder="Chức danh" required value="<?php echo $chucdanh ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mã bộ</label>
                                            <input type="text" name="mabo" class="form-control"
                                                placeholder="Mã bộ" required value="<?php echo $mabo ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mã nhóm</label>
                                            <input type="text" name="manhom" class="form-control"
                                                placeholder="Mã nhóm" required value="<?php echo $manhom ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Email đăng nhập</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="email" required
                                                    value="<?php echo $email ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mật khẩu</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                        <i class="fas fa-lock" id="lockIcon"></i>
                                                    </span>
                                                </div>
                                                <input type="password" class="form-control" name="password" required
                                                    value="<?php echo $pass ?>" id="passwordInput">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <button type="submit" name="btn" class="btn btn-success">Cập nhật</button>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này? Mọi dữ liệu liên quan đến nhân viên sẽ bị xóa!');"
                                            href="xoa_nv.php?x=<?php echo $manv ?>" class="btn btn-danger">Xoá nhân viên</a>
                                    </div>
                                </div>
                                <?php
                                if (isset($_POST['btn'])) {
                                    $ten = $_POST['ten'];
                                    $gt = $_POST['gioitinh'];
                                    // $ngaysinh = $_POST['ngay'];
                                    $mabo = $_POST['mabo'];
                                    $manhom = $_POST['manhom'];
                                    $bophan = $_POST['bophan'];
                                    $email = $_POST['email'];
                                    $password = $_POST['password'];
                                    // $calam = $_POST['calam'];
                                    // // $ngaylam = $_POST['ngay_lam'];
                                    // $luong = "";
                                    // if ($bophan == 1) {
                                    //     $luong = 1;
                                    // } elseif ($bophan == 3) {
                                    //     if ($calam == 1 or $calam == 2) {
                                    //         $luong = 2;
                                    //     } else {
                                    //         $luong = 4;
                                    //     }
                                    // } else {
                                    //     if ($calam == 1 or $calam == 2) {
                                    //         $luong = 3;
                                    //     } else {
                                    //         $luong = 4;
                                    //     }
                                    // }
                                    $luong = 3;
                                    $sql = "UPDATE nhan_vien SET Hoten='$ten',ID_bophan='$bophan',He_so_luong='$luong',Ma_bo='$mabo',Ma_nhom='$manhom',username='$email', pass ='$password' WHERE Ma_nv='$manv'";
                                    $result = mysqli_query($conn, $sql) or die("Có lỗi xảy ra: " . mysqli_error($conn));
                                    if ($result == true) {
                                        echo "Thành Công ! Hãy vào <a href='ql_nhan_vien.php'>Danh sách nhân viên </a> để xem lại";
                                    } else {
                                        echo "Lỗi";
                                    }
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                    <!-- <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Thưởng</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nh��n viên</th>
                                        <th>Số tiền</th>
                                        <th>Ngày thực hiện</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $year = date('Y');
                                    $month = date('m');
                                    $sql = " select * from thuong_phat where Ma_nv = '$manv' and Loai_hinh = 'Thưởng' and MONTH(Ngay_thuc_hien) = '$month' and YEAR(Ngay_thuc_hien) ='$year'";
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    $thuong = 0;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $ten; ?></td>
                                            <td><?php echo number_format($row['So_tien']); ?><i> đồng</i></td>
                                            <td><?php echo $row['Ngay_thuc_hien']; ?></td>
                                        </tr>
                                    <?php
                                        $thuong = $row['So_tien'];
                                        if ($thuong == null) {
                                            $thuong = 0;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Phạt</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th>Số tiền</th>
                                        <th>Ngày</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = " select * from thuong_phat where Ma_nv = '$manv' and Loai_hinh = 'Phạt' and MONTH(Ngay_thuc_hien) = '$month' and YEAR(Ngay_thuc_hien) ='$year'";
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    $phat = 0;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $ten; ?></td>
                                            <td><?php echo number_format($row['So_tien']); ?><i> đồng</i></td>
                                            <td><?php echo $row['Ngay_thuc_hien']; ?></td>
                                        </tr>
                                    <?php $phat = $row['So_tien'];
                                        if ($phat == null) {
                                            $phat = 0;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ứng lương</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th>Số tiền</th>
                                        <th>Ngày thực hiện</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "select * from ung_luong where Ma_nv = '$manv'and MONTH(Ngay_ung) = '$month' and YEAR(Ngay_ung) ='$year'";
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    global $ung;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $ten; ?></td>
                                            <td><?php echo number_format($row['So_tien']); ?><i> đồng</i></td>
                                            <td><?php echo $row['Ngay_ung']; ?></td>
                                        </tr>
                                    <?php $ung = $row['So_tien'];
                                        if ($ung == null) {
                                            $ung = 0;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Số ngày đi làm</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th>Số ngày</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "select * from cham_cong where Ma_nv = '$manv' and Tinh_trang = 'Đi làm' and MONTH(Ngay) = '$month' and YEAR(Ngay) ='$year'";
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    if ($result) {
                                        $days = mysqli_num_rows($result);
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $ten; ?></td>
                                        <td><?php echo $days; ?></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div> -->
                    <!-- <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><b>Tổng lơng</b> </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                            $tong = (($luong / 28) * $days) + $thuong - $phat - $ung;
                            // echo number_format((int)$tong);
                            // echo '((' . number_format($luong) . '/' . '28' . ')' . 'x' . $days . ')' . '+' . number_format($thuong) . '-' . number_format($phat) . '-' . number_format($ung) . '=' . number_format((int)$tong);
                            ?>
                            <form action="" method="post">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nhân viên</th>
                                            <th>Lương cơ bản</th>
                                            <th>Số ngày làm</th>
                                            <th>Thưởng</th>
                                            <th>Phạt</th>
                                            <th>Ứng</th>
                                            <th>Thực lĩnh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $ten; ?></td>
                                            <td><?php echo number_format($luong) ?></td>
                                            <td><?php echo $days; ?></td>
                                            <td><?php echo  number_format($thuong) ?></td>
                                            <td><?php echo  number_format($phat) ?></td>
                                            <td><?php echo  number_format($ung) ?></td>
                                            <td><?php echo number_format((int)$tong) . ' đồng' ?></td>
                                        </tr>
                                    </tbody>
                                    <button type="submit" name="btnn" class="btn btn-primary">Tính lương</button>
                                </table>
                            </form>
                            <?php
                            if (isset($_POST['btnn'])) {
                                $today = date("Y/m/d");
                                $sql = "INSERT INTO nhan_luong values('$manv','$hsluong','$days','$thuong','$phat','$ung','$tong',' $today','Chưa thanh toán')";
                                $result = mysqli_query($conn, $sql) or die("câu lệnh truy vấn sai");
                                $sql2 = "DELETE FROM `thuong_phat` WHERE Ma_nv = '$manv'";
                                $sql3 = "DELETE FROM `ung_luong` WHERE Ma_nv = '$manv'";
                                $sql4 = "DELETE FROM `cham_cong` WHERE Ma_nv= '$manv'";
                                $result2 = mysqli_query($conn, $sql2) or die("câu lệnh truy vấn sai");
                                $result3 = mysqli_query($conn, $sql3) or die("câu lệnh truy vấn sai");
                                $result4 = mysqli_query($conn, $sql4) or die("câu lệnh truy vấn sai");
                                if ($result == true) {
                                    $result2;
                                    $result3;
                                    $result4;
                                    echo "<a href='bang_luong_test.php'>Lương</a>";
                                }
                            } ?>
                        </div>
                    </div> -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require 'form.html' ?>
<?php require 'footer.php' ?>
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');
        const lockIcon = document.getElementById('lockIcon');

        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the lock icon
        lockIcon.classList.toggle('fa-lock');
        lockIcon.classList.toggle('fa-unlock');
    });
</script>