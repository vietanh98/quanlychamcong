<?php require 'table.html' ?>
<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$email = $_SESSION['username'];
$sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";
$resultbp = $conn->query($sqlpb);
$row1 = $resultbp->fetch_assoc();
$Idbp = $row1['ID'];
$sqlnv = "SELECT * FROM nhan_vien WHERE ID_bophan = '$Idbp'";
$nhan_vien = $conn->query($sqlnv);
$rownv = $nhan_vien->fetch_assoc();
$sqlcalam = "SELECT * FROM ca_lam_viec";
$ca_lam = $conn->query($sqlcalam);
?>
<div class="content-wrapper" style="min-height: 353px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Phân ca làm</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Phân ca làm</a></li>
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
                    <h3 class="card-title">Phân ca làm cho nhân viên</h3>
                </div>
                <form method="post">
                    <div class="card-body">

                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Tên nhân viên</label>
                                    <select required class="form-control" name="employee" id="">
                                        <option>Chọn nhân viên</option>
                                        <option value="<?php echo $rownv['Ma_nv'] ?>"><?php echo $rownv['Hoten'] ?>
                                        </option>
                                        <?php
                                        if ($nhan_vien->num_rows > 0) {
                                            while ($row = $nhan_vien->fetch_assoc()) {
                                                echo '<option value="' . $row['Ma_nv'] . '">' . $row['Hoten'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Ca làm việc</label>
                                    <select required class="form-control" name="ca_lam" id="">
                                        <option>Chọn ca làm</option>
                                        <?php
                                        if ($ca_lam->num_rows > 0) {
                                            while ($row = $ca_lam->fetch_assoc()) {
                                                // Check the value of Tenca and set the display value accordingly
                                                $displayValue = ($row['Tenca'] == 'K') ? 'K - Nghỉ không phép' : (($row['Tenca'] == 'OFF') ? 'OFF - Nghỉ có phép' : $row['Tenca'] . ' - ' . $row['Gio_bat_dau'] . ' - ' . $row['Gio_ket_thuc']);
                                                echo '<option value="' . $row['ID'] . '">' . $displayValue . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ngày làm việc</label>
                                    <input type="date" name='ngay_lam' required class="form-control">
                                </div>
                            </div> -->
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Từ ngày </label>
                                    <input type="date" name="ngay_bat_dau" required class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Đến ngày</label>
                                    <input type="date" name="ngay_ket_thuc" required class="form-control">
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                    </div>

                    <?php
                    if (isset($_POST['btn'])) {
                        $id_nv = $_POST['employee'];
                        $id_calam = $_POST['ca_lam'];
                        $ngay_bat_dau = $_POST['ngay_bat_dau'];
                        $ngay_ket_thuc = $_POST['ngay_ket_thuc'];

                        // Chuyển đổi ngày bắt đầu và ngày kết thúc sang đối tượng DateTime
                        $date_start = new DateTime($ngay_bat_dau);
                        $date_end = new DateTime($ngay_ket_thuc);

                        // Kiểm tra nếu ngày kết thúc trước ngày bắt đầu
                        if ($date_end < $date_start) {
                            echo "Ngày kết thúc phải sau hoặc bằng ngày bắt đầu!";
                            exit();
                        }

                        // Lặp qua từng ngày từ ngày bắt đầu đến ngày kết thúc
                        while ($date_start <= $date_end) {
                            $ngay = $date_start->format('Y-m-d');

                            // Kiểm tra ca làm đã tồn tại chưa
                            $kq = "SELECT * FROM phan_ca_lam WHERE Ma_nv='$id_nv' AND id_calam = '$id_calam' AND ngay ='$ngay'";
                            $kq_con = mysqli_query($conn, $kq) or die('Kết nối thất bại');
                            $dem = mysqli_num_rows($kq_con);

                            if ($dem > 0) {
                                echo "Ca làm vào ngày $ngay đã tồn tại cho nhân viên này.<br>";
                            } else {
                                // Thêm ca làm mới vào cơ sở dữ liệu cho ngày này
                                $sql = "INSERT INTO phan_ca_lam (Ma_nv, id_calam, ngay) VALUES ('$id_nv', '$id_calam', '$ngay')";
                                $result = mysqli_query($conn, $sql);
                                if ($result) {
                                    echo "Thêm ca làm cho ngày $ngay thành công! <a href='ql_ca_lam.php'>Danh sách</a><br>";
                                } else {
                                    echo "Lỗi khi thêm ca làm cho ngày $ngay!<br>";
                                }
                            }

                            // Tăng ngày lên 1 ngày
                            $date_start->modify('+1 day');
                        }
                    }
                    ?>

                </form>
            </div>
    </section>
</div>
<?php require 'footer.php' ?>