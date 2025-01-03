<?php require 'table.html' ?>
<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$email = $_SESSION['username'];
$sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";
$resultbp = $conn->query($sqlpb);
$row1 = $resultbp->fetch_assoc();
$Idbp = $row1['ID'];

$nhan_vien = $conn->query("SELECT * FROM nhan_vien WHERE Id_bophan = '$Idbp'");
?>
<div class="content-wrapper" style="min-height: 353px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Thêm tăng ca</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Thên tăng ca</a></li>
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
                    <h3 class="card-title">Thêm tăng ca</h3>
                </div>
                <form method="post">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Tên nhân viên</label>
                                    <select required class="form-control" name="employee" id="">
                                        <option>Chọn nhân viên</option>
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

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ngày</label>
                                    <input type="date" name='ngay' required class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Giờ bắt đầu</label>
                                    <input type="time" name='gio_bat_dau' required class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Giờ kết thúc</label>
                                    <input type="time" name='gio_ket_thuc' required class="form-control">
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                    </div>

                    <?php
                    if (isset($_POST['btn'])) {
                        $employee = $_POST['employee'];
                        $ngay = $_POST['ngay'];
                        $start = $_POST['gio_bat_dau'];
                        $end = $_POST['gio_ket_thuc'];

                        $sql = "INSERT INTO tang_ca (Ma_nv, Ngay, Gio_bat_dau, Gio_ket_thuc) VALUES ('$employee', '$ngay','$start', '$end')";
                        $result = mysqli_query($conn, $sql);
                        if ($result == true) {
                            echo "<div class='alert alert-success'>Thêm thành công! <a href='ql_tang_ca.php'>Danh sách tăng ca</a> </div>";
                        } else {
                            echo "<div class='alert alert-danger'>Có lỗi xảy ra. Vui lòng thử lại!</div>";
                        }
                    }
                    ?>
                </form>
            </div>
    </section>
</div>
<?php require 'footer.php' ?>