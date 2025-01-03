<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$nhan_vien = $conn->query("SELECT * FROM nhan_vien");
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$he_so_luong = $conn->query("SELECT * FROM luong"); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Tính lương</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                        <li class="breadcrumb-item active">Bảng lương</li>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Tháng <?php $today = date("m/Y");
                                                            echo $today; ?></h3>
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
                            <table id="example" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th hidden>Mã nhân viên</th>
                                        <th>Họ tên</th>
                                        <th>Hệ số lương</th>
                                        <th>Số ngày làm</th>
                                        <th>Thưởng</th>
                                        <th>Phạt</th>
                                        <th>Ứng</th>
                                        <th>Tổng lương</th>
                                        <th>Tính</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $year = date('Y');
                                    $month = date('m');
                                    $sql = "SELECT nhan_vien.*,luong.Luong_co_ban
                                    FROM nhan_vien
                                    JOIN luong ON nhan_vien.He_so_luong = luong.He_so_luong GROUP BY Ma_nv;";
                                    $result = mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $manv = $row['Ma_nv'];
                                        require_once "../src/function.php";
                                        //Số day đi làm
                                        $day = $conn->query("SELECT * FROM cham_cong where Ma_nv = '$manv'and MONTH(Ngay) = '$month' and YEAR(Ngay) ='$year'");
                                        $days = $day->num_rows;
                                        //Thưởng
                                        $sql2 = " SELECT sum(`So_tien`) FROM `thuong_phat` WHERE Ma_nv = '$manv' and Loai_hinh = 'Thưởng' and MONTH(Ngay_thuc_hien) = '$month' and YEAR(Ngay_thuc_hien) ='$year' ";
                                        $result2 = mysqli_query($conn, $sql2) or die("Câu truy vấn sai!");
                                        $thuong = mysqli_fetch_array($result2);
                                        //Phạt
                                        $sql3 = " SELECT sum(`So_tien`) FROM `thuong_phat` WHERE Ma_nv = '$manv' and Loai_hinh = 'Phạt'and MONTH(Ngay_thuc_hien) = '$month' and YEAR(Ngay_thuc_hien) ='$year'";
                                        $result3 = mysqli_query($conn, $sql3) or die("Câu truy vấn sai!");
                                        $phat = mysqli_fetch_array($result3);
                                        //Ứng
                                        $sql4 = " SELECT sum(`So_tien`) FROM `ung_luong` WHERE Ma_nv = '$manv'and MONTH(Ngay_ung) = '$month' and YEAR(Ngay_ung) ='$year'";
                                        $result4 = mysqli_query($conn, $sql4) or die("Câu truy vấn sai!");
                                        $ung = mysqli_fetch_array($result4);
                                        //Time
                                        $thang = date("Y/m/d");
                                    ?>
                                        <tr>
                                            <td hidden><?php echo $row['Ma_nv']; ?></td>
                                            <td><?php echo $row['Hoten']; ?></td>
                                            <td><a href="ql_luong.php"> <?php echo number_format($row['He_so_luong']); ?></a></td>
                                            <td><?php echo $days ?></td>
                                            <td><?php if ($thuong[0] == "") {
                                                    echo 0;
                                                } else {
                                                    echo number_format($thuong[0]);
                                                } ?></td>
                                            <td><?php if ($phat[0] == "") {
                                                    echo 0;
                                                } else {
                                                    echo number_format($phat[0]);
                                                }  ?></td>
                                            <td><?php if ($ung[0] == "") {
                                                    echo 0;
                                                } else {
                                                    echo number_format($ung[0]);
                                                }  ?></td>
                                            <td><?php $tong = (($row['Luong_co_ban'] / 28) * $days) + $thuong[0] - $phat[0] - $ung[0];
                                                echo number_format((int)$tong) ?>
                                            </td>
                                            <td><button class="btn btn-success" onclick="window.location.href='tinh_luong.php?Manv=<?php echo $row['Ma_nv'] ?>&&Hs=<?php echo $row['He_so_luong'] ?>&&Tong=<?php echo $tong ?>&&Day=<?php echo $days ?>'"> <i class="fas fa-check"></i></button> </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
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
<?php require 'footer_ql.php' ?>