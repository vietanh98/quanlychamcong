<?php require 'table.html' ?>
<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$luong = $conn->query("SELECT * FROM luong"); ?>
<?php
$tenbp = $_GET['x'];
?>
<div class="content-wrapper" style="min-height: 353px;">
    <div class="content-header">
        <div class="container-fluid">
            <!-- <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Thêm nhân viên</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Nhân viên</a></li>
                        <li class="breadcrumb-item active">Thêm</li>
                    </ol>
                </div>
            </div> -->
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Thêm <?php echo $tenbp ?></h3>

                </div>
                <form method="post">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mã nhân viên</label>
                                    <input type="text" name="manv" class="form-control" placeholder="Mã nhân viên" required value="NV">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Tên nhân viên</label>
                                    <input type="text" name="ten" class="form-control" required placeholder="Tên nhân viên">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Giới tính</label><br>
                                    <input type="radio" name="gioitinh" value="Nam" checked="checked">
                                    <label>Nam</label>
                                    <input type="radio" name="gioitinh" value="Nữ">
                                    <label>Nữ</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ngày sinh</label>
                                    <input type="date" name="ngay" required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Quê quán</label>
                                    <input type="text" name="que" required class="form-control" placeholder="Quê quán">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>SĐT</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="sdt" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Bộ phận</label>
                                    <input name="bophan" value="<?php echo $tenbp ?>" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ca làm việc</label>
                                    <select name="calam" id="calam" required class="form-control">
                                        <?php while ($row = $ca_lam_viec->fetch_assoc()) : ?>
                                            <option value="<?php echo $row['ID'] ?>"><?php echo $row['Gio_bat_dau'] . ' đến ' . $row['Gio_ket_thuc'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ngày làm việc</label>
                                    <input type="date" name="ngay_lam" required class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                    </div>
                    <?php
                    if (isset($_POST['btn'])) {
                        $ma = $_POST['manv'];
                        $ten = $_POST['ten'];
                        $gt = $_POST['gioitinh'];
                        $ngay = $_POST['ngay'];
                        $qq = $_POST['que'];
                        $sdt = $_POST['sdt'];
                        $calam = $_POST['calam'];
                        $ngaylam = $_POST['ngay_lam'];
                        $tenbp = "";
                        $bophan = "";
                        if ($tenbp == "Quản trị") {
                            $bophan = 1;
                        } elseif ($tenbp == "Thu ngân") {
                            $bophan = 2;
                        } elseif ($tenbp == "Pha chế") {
                            $bophan = 3;
                        } else {
                            $bophan = 4;
                        }
                        $luong = "";
                        if ($bophan == 1) {
                            $luong = 1;
                        } elseif ($bophan == 3) {
                            if ($calam == 1 or $calam == 2) {
                                $luong = 2;
                            } else {
                                $luong = 4;
                            }
                        } else {
                            if ($calam == 1 or $calam == 2) {
                                $luong = 3;
                            } else {
                                $luong = 4;
                            }
                        }
                        // Câu SQL lấy danh sách
                        $sql = " select * from nhan_vien where Ma_nv='$ma'";
                        // Thực thi câu truy vấn và gán vào $result
                        $result = mysqli_query($conn, $sql);
                        // Kiểm tra số lượng record trả về có lơn hơn 0
                        // Nếu lớn hơn tức là có kết quả, ngược lại sẽ không có kết quả
                        $dem = mysqli_num_rows($result);
                        if ($dem > 0) {
                            echo "Mã Nhân Viên Đã Tồn Tại";
                            exit();
                        } else {
                            $sql = "INSERT INTO nhan_vien VALUES('$ma','$ten','$gt','$ngay','$qq','$sdt','$bophan','$calam','$luong','$ngaylam')";
                            $result = mysqli_query($conn, $sql);
                            if ($result == true) {
                                echo "Thêm Thành Công !Hãy vào <a href='ql_nhan_vien.php'>Danh sách nhân viên </a> để xem lại";
                            }
                        }
                    }
                    ?>
                </form>

            </div>
    </section>
</div>
<?php require 'footer_table.php' ?>