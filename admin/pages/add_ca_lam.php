<?php require 'table.html' ?>
<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$nhan_vien = $conn->query("SELECT * FROM nhan_vien");
?>
<div class="content-wrapper" style="min-height: 353px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Thêm ca làm</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Ca làm</a></li>
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
                    <h3 class="card-title">Thêm ca làm</h3>
                </div>
                <form method="post">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Tên ca làm</label>
                                    <input type="text" name='ten_ca' required class="form-control">
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
                        <!-- <div class="row mb-2">
                            <div class="col-sm-12">
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
                        </div> -->
                        <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                    </div>

                    <?php
                    if (isset($_POST['btn'])) {
                        $tenca = $_POST['ten_ca'];
                        $start = $_POST['gio_bat_dau'];
                        $end = $_POST['gio_ket_thuc'];
                        // $employee = $_POST['employee'];
                        $kq = " select * from ca_lam_viec where Tenca = '$tenca' AND Gio_bat_dau='$start'";
                        $kq_con = mysqli_query($conn, $kq);
                        $dem = mysqli_num_rows($kq_con);
                        if ($dem > 0) {
                            echo "Ca làm Đã Tồn Tại";
                            exit();
                        } else {
                            $sql = "INSERT INTO ca_lam_viec VALUES(ID,'$tenca','$start','$end')";
                            $result = mysqli_query($conn, $sql);
                            if ($result == true) {
                                echo "Thêm Thành Công !Hãy vào <a href='ql_ca_lam.php'>Danh sách </a> để xem lại";
                            }
                        }

                        // $sql = "INSERT INTO ca_lam_viec VALUES(ID,'$start','$end','$employee')";
                        //     $result = mysqli_query($conn, $sql);
                        //     if ($result == true) {
                        //         echo "Thêm Thành Công !Hãy vào <a href='ql_ca_lam.php'>Danh sách </a> để xem lại";
                        //     }
                    }
                    ?>
                </form>
            </div>
    </section>
</div>
<?php require 'footer.php' ?>