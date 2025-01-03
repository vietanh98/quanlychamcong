<?php require 'header.php';
require_once "../src/function.php";
require_once "../src/db.php";
global $conn;
date_default_timezone_set('Asia/Ho_Chi_Minh');
$nv = $conn->query("SELECT * FROM nhan_vien");
$datetime = new DateTime();
$curtime = $datetime->format('H:i:s');

$soluongnv = $nv->num_rows;
$today = date("d/m/Y");
$thuong = $conn->query("SELECT * FROM `nhan_luong`");
$tong_doanh_thu = 0;
$doanh_thu_thang = 0;
$days = 0;
while ($row = $thuong->fetch_assoc()) {
    $days += $row['So_ngay_lam'];
    if ($row['Tinh_trang'] == 'Đã thanh toán') {
        $tong_doanh_thu += $row['Tong'];

        if (date('m', strtotime($row['Thoi_gian'])) == date('m')) {
            $doanh_thu_thang += $row['Tong'];
        }
    }
}
if (isset($_SESSION['Ma_nv'])) {
    $manv = $_SESSION['Ma_nv'];
    $nv_ca = $conn->query("SELECT clv.*
FROM phan_ca_lam pcl
JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
WHERE pcl.Ma_nv = '$manv' AND pcl.ngay = CURDATE()");

    $nv_ca_bu = $conn->query("SELECT *
FROM cham_bu 
WHERE Ma_nv = '$manv' AND status = 1 AND Ngay_cham_bu = CURDATE()");

    $nv_ca_tang = $conn->query("SELECT *
FROM tang_ca 
WHERE Ma_nv = '$manv' AND  status = 1 AND Ngay = CURDATE()");
    // check xem đã checkin chưa
    $sql_check = "SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NULL AND role = 0";
    $result_check = mysqli_query($conn, $sql_check);
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Trang chủ</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right"><b>
                            <?php
                            echo $today;
                            ?> <span id="timer"></span></b>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->

    <?php if ($_SESSION['role'] == 'employee') { ?>
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-6 col-6 mb-2">
                        <h5>Ca làm việc của bạn hôm nay</h5>
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th>Giờ bắt đầu</th>
                                <th>Giờ kết thúc</th>
                                <th>Thời gian</th>
                            </tr>
                            <?php
                            $gio_bd_normal = '';
                            ?>
                            <?php while ($row = $nv_ca->fetch_assoc()) {
                                $gio_bd_normal = $row['Gio_bat_dau'];
                            ?>

                                <tr>
                                    <td><?php echo $row['Gio_bat_dau'] ?></td>
                                    <td><?php echo $row['Gio_ket_thuc'] ?></td>
                                    <td><?php echo (strtotime($row['Gio_ket_thuc']) - strtotime($row['Gio_bat_dau'])) / 3600 . ' hours'; ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </table>
                    </div>


                    <?php if ($gio_bd_normal && $curtime > $gio_bd_normal) { ?>
                        <div class="col-lg-6 col-12 mt-4 pt-2">
                            <?php if (mysqli_num_rows($result_check) > 0) { ?>

                                <div class="col-lg-12 col-12 mb-2">
                                    <button style="font-weight:bold" class="btn btn-success w-25">Đã Check in</button>
                                </div>

                                <div class="col-lg-12 col-12">
                                    <form action="checkout.php" method="POST">
                                        <input name="Ma_nv" type="hidden" value="<?php echo ($manv) ?>">
                                        <button type="submit" style="font-weight:bold" class="btn btn-danger w-25">Check
                                            out</button>
                                    </form>
                                </div>

                                <?php } else {
                                $sql_check_done = "SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NOT NULL AND role = 0 AND Ngay = CURDATE()";
                                $result_done = mysqli_query($conn, $sql_check_done);

                                if (mysqli_num_rows($result_done) == 0) {
                                ?>

                                    <div class="col-lg-12 col-12 mb-2">
                                        <form action="checkin.php" method="POST">
                                            <input name="Ma_nv" type="hidden" value="<?php echo ($manv) ?>">
                                            <button type="submit" style="font-weight:bold" class="btn btn-success w-25">Check
                                                in</button>
                                        </form>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <button style="font-weight:bold" class="btn btn-danger w-25">Check out</button>
                                    </div>

                                <?php } else { ?>

                                    <div class="col-lg-12 col-12 mb-2">
                                        <h5 style="font-weight:bold" class="btn btn-success w-25">&#10003; Đã chấm công</h5>
                                    </div>

                            <?php }
                            } ?>
                        </div>
                    <?php } ?>

                </div>

                <!-- Small boxes (Stat box) -->


            </div>
        </section>
        <?php if (mysqli_num_rows($nv_ca_bu) > 0) { ?>
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-6 col-6 mb-2">
                            <p class="text-success">Ca làm việc chấm bù OT</p>
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th>Giờ bắt đầu</th>
                                    <th>Giờ kết thúc</th>
                                    <th>Thời gian</th>
                                </tr>

                                <?php while ($row1 = $nv_ca_bu->fetch_assoc()) {

                                    $gio_bd_OT = $row1['Gio_bat_dau'];
                                ?>

                                    <tr>
                                        <td><?php echo $row1['Gio_bat_dau'] ?></td>
                                        <td><?php echo $row1['Gio_ket_thuc'] ?></td>
                                        <td><?php echo (strtotime($row1['Gio_ket_thuc']) - strtotime($row1['Gio_bat_dau'])) / 3600 . ' hours'; ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </table>
                        </div>


                        <?php if ($curtime > $gio_bd_OT) { ?>
                            <div class="col-lg-6 col-12 mt-4 pt-2">
                                <?php $sql_check_cb = "SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NULL AND role = 1";
                                $result_check_cb = mysqli_query($conn, $sql_check_cb); ?>
                                <?php if (mysqli_num_rows($result_check_cb) > 0) { ?>

                                    <div class="col-lg-12 col-12 mb-2">
                                        <button style="font-weight:bold" class="btn btn-success w-25">Đã Check in</button>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <form action="checkout_chambu.php" method="POST">
                                            <input name="Ma_nv" type="hidden" value="<?php echo ($manv) ?>">
                                            <button type="submit" style="font-weight:bold" class="btn btn-danger w-25">Check
                                                out</button>
                                        </form>
                                    </div>

                                    <?php } else {
                                    $sql_check_done1 = "SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NOT NULL AND role = 1 AND Ngay = CURDATE()";
                                    $result_done1 = mysqli_query($conn, $sql_check_done1);

                                    if (mysqli_num_rows($result_done1) == 0) {
                                    ?>

                                        <div class="col-lg-12 col-12 mb-2">
                                            <form action="checkin_chambu.php" method="POST">
                                                <input name="Ma_nv" type="hidden" value="<?php echo ($manv) ?>">
                                                <button type="submit" style="font-weight:bold" class="btn btn-success w-25">Check
                                                    in</button>
                                            </form>
                                        </div>

                                        <div class="col-lg-12 col-12">
                                            <button style="font-weight:bold" class="btn btn-danger w-25">Check out</button>
                                        </div>

                                    <?php } else { ?>

                                        <div class="col-lg-12 col-12 mb-2">
                                            <h5 style="font-weight:bold" class="btn btn-success w-25">&#10003; Đã chấm công</h5>
                                        </div>

                                <?php }
                                } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Small boxes (Stat box) -->


                </div>
            </section>
        <?php  } ?>

        <?php if (mysqli_num_rows($nv_ca_tang) > 0) { ?>
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-6 col-6 mb-2">
                            <p class="text-primary">Ca làm việc tăng ca</p>
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th>Giờ bắt đầu</th>
                                    <th>Giờ kết thúc</th>
                                    <th>Thời gian</th>
                                </tr>

                                <?php while ($row2 = $nv_ca_tang->fetch_assoc()) {

                                    $gio_bd_tc = $row2['Gio_bat_dau'];
                                ?>

                                    <tr>
                                        <td><?php echo $row2['Gio_bat_dau'] ?></td>
                                        <td><?php echo $row2['Gio_ket_thuc'] ?></td>
                                        <td><?php echo (strtotime($row2['Gio_ket_thuc']) - strtotime($row2['Gio_bat_dau'])) / 3600 . ' hours'; ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </table>
                        </div>


                        <?php if ($curtime > $gio_bd_tc) { ?>
                            <div class="col-lg-6 col-12 mt-4 pt-2">
                                <?php $sql_check_tc = "SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NULL AND role = 2";
                                $result_check_tc = mysqli_query($conn, $sql_check_tc); ?>
                                <?php if (mysqli_num_rows($result_check_tc) > 0) { ?>

                                    <div class="col-lg-12 col-12 mb-2">
                                        <button style="font-weight:bold" class="btn btn-success w-25">Đã Check in</button>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <form action="checkout_tangca.php" method="POST">
                                            <input name="Ma_nv" type="hidden" value="<?php echo ($manv) ?>">
                                            <button type="submit" style="font-weight:bold" class="btn btn-danger w-25">Check
                                                out</button>
                                        </form>
                                    </div>

                                    <?php } else {
                                    $sql_check_done2 = "SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NOT NULL AND role = 2 AND Ngay = CURDATE()";
                                    $result_done2 = mysqli_query($conn, $sql_check_done2);

                                    if (mysqli_num_rows($result_done2) == 0) {
                                    ?>

                                        <div class="col-lg-12 col-12 mb-2">
                                            <form action="checkin_tangca.php" method="POST">
                                                <input name="Ma_nv" type="hidden" value="<?php echo ($manv) ?>">
                                                <button type="submit" style="font-weight:bold" class="btn btn-success w-25">Check
                                                    in</button>
                                            </form>
                                        </div>

                                        <div class="col-lg-12 col-12">
                                            <button style="font-weight:bold" class="btn btn-danger w-25">Check out</button>
                                        </div>

                                    <?php } else { ?>

                                        <div class="col-lg-12 col-12 mb-2">
                                            <h5 style="font-weight:bold" class="btn btn-success w-25">&#10003; Đã chấm công</h5>
                                        </div>

                                <?php }
                                } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Small boxes (Stat box) -->


                </div>
            </section>
        <?php  } ?>

        <?php

        $id_bophan_query = $conn->query("
SELECT ID_bophan
FROM nhan_vien
WHERE Ma_nv = '$manv'
");

        if ($id_bophan_query) {
            $row = $id_bophan_query->fetch_assoc();
            $Idbp = $row['ID_bophan'];
        } else {
            // Handle query error
            echo "Error: " . $conn->error;
        }

        $currentMonth = date('m');
        $currentYear = date('Y');

        $sql = "SELECT nv.Hoten, nv.Ma_nv,nv.Ma_bo,nv.Ma_nhom,nv.chucdanh, DATE(pcl.ngay) AS ngay, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc, pcl.id
        FROM nhan_vien nv
        JOIN phan_ca_lam pcl ON nv.Ma_nv = pcl.Ma_nv
        JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
        WHERE nv.ID_bophan = '$Idbp'
        AND nv.Ma_nv = '$manv'
          AND MONTH(pcl.ngay) = '$currentMonth' 
          AND YEAR(pcl.ngay) = '$currentYear'";

        // Lấy số ngày trong tháng hiện tại
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $dates = range(1, $daysInMonth);

        $result1 = $conn->query($sql);
        $shifts = [];
        $colorMap = [];

        function getRandomColor()
        {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $hoten = $row1['Hoten'];
                $maNV = $row1['Ma_nv'];
                $maBo = $row1['Ma_bo'];
                $maNhom = $row1['Ma_nhom'];
                $chucDanh = $row1['chucdanh'];
                $ngay = date('j', strtotime($row1['ngay'])); // Chỉ lấy ngày
                $tenca = $row1['Tenca'];
                $gio_bat_dau = date('H:i', strtotime($row1['Gio_bat_dau']));
                $gio_ket_thuc = date('H:i', strtotime($row1['Gio_ket_thuc']));

                if (!isset($colorMap[$tenca])) {
                    $colorMap[$tenca] = getRandomColor(); // Gán màu ngẫu nhiên cho tên ca
                }

                // Lưu trữ dữ liệu ca làm việc vào mảng
                $shifts[$hoten][$ngay] = [
                    'tenca' => $tenca,
                    'gio_bat_dau' => $gio_bat_dau,
                    'gio_ket_thuc' => $gio_ket_thuc,
                    'maNV' => $maNV,
                    'maBo' => $maBo,
                    'maNhom' => $maNhom,
                    'chucDanh' => $chucDanh,
                ];
            }
        }


        ?>

        <style>
            table {
                width: 100%;
                /* Đặt chiều rộng bảng là 100% */
                table-layout: fixed;
                /* Tự động điều chỉnh chiều rộng các cột */
            }

            td {
                /* overflow: hidden; Ẩn phần thừa */
                text-overflow: ellipsis;
                /* Hiển thị dấu ba chấm nếu nội dung quá dài */
                white-space: nowrap;
                /* Không xuống dòng */
            }

            th.namenv {
                width: 20%;
                /* Chiều rộng cố định cho cột tên nhân viên */
            }

            th.manv {
                width: 18%;
                /* Chiều rộng cố định cho cột tên nhân viên */
            }

            th.namenv,
            th.thdate {
                background: #e9984c;
            }

            th {
                width: 8%;
                /* Đặt chiều rộng cho cột ngày */
            }

            td,
            th {
                text-align: center;
                /* Căn giữa nội dung trong ô */
            }

            td.tdname {
                white-space: normal;
                /* Cho phép nội dung xuống dòng trong cột tên */
            }
        </style>

        <section class="content">
            <div class="container-fluid">
                <p class="text-danger">Ca làm việc của bạn</p>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2"
                                style="padding-left:5px;vertical-align: middle;background: lightgreen; width:8%">STT</th>
                            <th class="manv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Mã NV
                            </th>
                            <th class="namenv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Tên
                            </th>
                            <th class="manv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Mã bộ
                            </th>
                            <th class="manv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Chức danh
                            </th>
                            <?php foreach ($dates as $date): ?>
                                <th class="thdate"><?= $date ?></th>
                            <?php endforeach; ?>
                        </tr>

                        <tr>
                            <?php foreach ($dates as $date): ?>
                                <th style="padding-left: 2px" class="date thdate">
                                    <?= date('D', mktime(0, 0, 0, $currentMonth, $date, $currentYear)) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $s = 0 ?>

                        <?php foreach ($shifts as $hoten => $shiftsData): ?>
                            <tr>
                                <td><?php echo $s += 1; ?></td>

                                <td><?= $shiftsData[array_key_first($shiftsData)]['maNV'] ?? ''; ?></td>
                                <td class="tdname"><?= $hoten ?></td>
                                <td><?= $shiftsData[array_key_first($shiftsData)]['maBo'] ?? ''; ?></td>
                                <td><?= $shiftsData[array_key_first($shiftsData)]['maNhom'] ?? ''; ?></td>
                                <td><?= $shiftsData[array_key_first($shiftsData)]['chucDanh'] ?? ''; ?></td>

                                <?php foreach ($dates as $date): ?>


                                    <?php if (isset($shiftsData[$date])): ?>
                                        <?php
                                        $tenca = $shiftsData[$date]['tenca'];
                                        $color = $colorMap[$tenca]; // Lấy màu đã gán
                                        ?>
                                        <td style="background-color:<?= $color ?>;" class="font-weight-bold">
                                            <?= $shiftsData[$date]['tenca'] ?></td>
                                    <?php else: ?>
                                        <td class="text-secondary">K</td>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <p class="text-primary">Bảng Chú Thích</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Màu sắc</th>
                            <th>Tên ca làm</th>
                            <th>Giờ bắt đầu</th>
                            <th>Giờ kết thúc</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // Lặp lại để lấy giờ bắt đầu và kết thúc cho từng ca
                        $shiftTimes = [];
                        foreach ($shifts as $shiftsData) {
                            foreach ($shiftsData as $data) {
                                $tenca = $data['tenca'];
                                if (!isset($shiftTimes[$tenca])) {
                                    $shiftTimes[$tenca] = [
                                        'gio_bat_dau' => $data['gio_bat_dau'],
                                        'gio_ket_thuc' => $data['gio_ket_thuc'],
                                    ];
                                } else {
                                    // Nếu ca đã có, chỉ cần kiểm tra và cập nhật nếu cần
                                    $existingStart = $shiftTimes[$tenca]['gio_bat_dau'];
                                    $existingEnd = $shiftTimes[$tenca]['gio_ket_thuc'];
                                    if ($data['gio_bat_dau'] < $existingStart) {
                                        $shiftTimes[$tenca]['gio_bat_dau'] = $data['gio_bat_dau'];
                                    }
                                    if ($data['gio_ket_thuc'] > $existingEnd) {
                                        $shiftTimes[$tenca]['gio_ket_thuc'] = $data['gio_ket_thuc'];
                                    }
                                }
                            }
                        }
                        ?>

                        <?php foreach ($colorMap as $tenca => $color): ?>
                            <tr>
                                <td style="background-color: <?= $color ?>;"></td>
                                <td><?= $tenca ?></td>
                                <td><?= $shiftTimes[$tenca]['gio_bat_dau'] ?></td>
                                <td><?= $shiftTimes[$tenca]['gio_ket_thuc'] ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td style="background-color: #fff;"></td>
                            <td>K</td>
                            <td colspan="2">Không có ca làm việc</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    <?php } else if ($_SESSION['role'] == 0) { ?>
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <!-- <div class="col-lg-3 col-6">
                              <div class="small-box bg-primary">
                                <div class="inner">
                                  <h3><?php echo number_format($tong_doanh_thu, 0) . ' đ' ?></h3>
                                  <p>Tổng chi</p>

                                </div>
                                <div class="icon">
                                  <i class="ion ion-bag"></i>
                                </div>
                                <a href="thong_ke_luong.php" class="small-box-footer">Chi tiết<i class="fas fa-arrow-circle-right"></i></a>
                              </div>
                            </div>
                            <div class="col-lg-3 col-6">
                              <div class="small-box bg-success">
                                <div class="inner">
                                  <h3><?php echo number_format($doanh_thu_thang) . ' đ' ?></h3>
                                  <p>THÁNG <?php echo date('m/Y') ?> </p>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="thong_ke.php" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                              </div>
                            </div> -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3> <?php
                                        echo $soluongnv;
                                        ?></h3>
                                <p>NHÂN VIÊN</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="ql_nhan_vien.php" class="small-box-footer">Chi tiết <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo ($days) ?></h3>
                                <p>SỐ NGÀY LÀM VIỆC NHÂN VIÊN</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="thong_ke.php" class="small-box-footer">Chi tiết<i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">

                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
            <!-- Bar chart -->
            <!-- <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="far fa-chart-bar"></i>
          Biểu đồ
        </h3>

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
        <canvas id="graph"></canvas>
      </div>

    </div> -->
            <!-- /.card -->
        </section>

    <?php } else if ($_SESSION['role'] == 1) {
        //       $username = $_SESSION['username'];
        //       $ql_ca = $conn->query("SELECT clv.*
        // FROM phan_ca_lam pcl
        // JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
        // WHERE pcl.Ma_nv = '$username' AND pcl.ngay = CURDATE()");
    ?>
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-6 col-6 mb-2">
                        <h5>Ca làm việc của bạn hôm nay</h5>
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th>Giờ bắt đầu</th>
                                <th>Giờ kết thúc</th>
                                <th>Thời gian</th>
                            </tr>
                            <?php
                            $gio_bd_normal = '';
                            ?>
                            <?php
                            if (isset($nv_ca)) {
                                while ($row = $nv_ca->fetch_assoc()) {
                                    $gio_bd_normal = $row['Gio_bat_dau'];
                                }
                            ?>

                                <tr>
                                    <td><?php echo $row['Gio_bat_dau'] ?></td>
                                    <td><?php echo $row['Gio_ket_thuc'] ?></td>
                                    <td><?php echo (strtotime($row['Gio_ket_thuc']) - strtotime($row['Gio_bat_dau'])) / 3600 . ' hours'; ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </table>
                    </div>


                    <?php if ($gio_bd_normal && $curtime > $gio_bd_normal) { ?>
                        <div class="col-lg-6 col-12 mt-4 pt-2">
                            <?php
                            $sql_check_ql = "SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NULL AND role = 0";
                            $result_check_ql = mysqli_query($conn, $sql_check_ql);
                            if (mysqli_num_rows($result_check_ql) > 0) { ?>

                                <div class="col-lg-12 col-12 mb-2">
                                    <button style="font-weight:bold" class="btn btn-success w-25">Đã Check in</button>
                                </div>

                                <div class="col-lg-12 col-12">
                                    <form action="checkout.php" method="POST">
                                        <input name="Ma_nv" type="hidden" value="<?php echo ($manv) ?>">
                                        <button type="submit" style="font-weight:bold" class="btn btn-danger w-25">Check
                                            out</button>
                                    </form>
                                </div>

                                <?php } else {
                                $sql_check_done_ql = "SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NOT NULL AND role = 0 AND Ngay = CURDATE()";
                                $result_done_ql = mysqli_query($conn, $sql_check_done_ql);

                                if (mysqli_num_rows($result_done_ql) == 0) {
                                ?>

                                    <div class="col-lg-12 col-12 mb-2">
                                        <form action="checkin.php" method="POST">
                                            <input name="Ma_nv" type="hidden" value="<?php echo ($manv) ?>">
                                            <button type="submit" style="font-weight:bold" class="btn btn-success w-25">Check
                                                in</button>
                                        </form>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <button style="font-weight:bold" class="btn btn-danger w-25">Check out</button>
                                    </div>

                                <?php } else { ?>

                                    <div class="col-lg-12 col-12 mb-2">
                                        <h5 style="font-weight:bold" class="btn btn-success w-25">&#10003; Đã chấm công</h5>
                                    </div>

                            <?php }
                            } ?>
                        </div>
                    <?php } ?>

                </div>

                <!-- Small boxes (Stat box) -->


            </div>
        </section>
        <!-- <section class="content">
                              <div class="container-fluid">
                              
                                <p class="text-danger">Danh sách nhân viên có ca làm hôm nay</p>
                                <div class="card">
                        <div class="card-body">
                        <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên nhân viên</th>
                            <th>Giới tính</th>
                            <th>Tên ca</th>
                            <th>Giờ bắt đầu</th>
                            <th>Giờ kết thúc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $email = $_SESSION['username'];
                        // $sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";
                        // $resultbp = $conn->query($sqlpb);
                        // $row1 = $resultbp->fetch_assoc();
                        // $Idbp = $row1['ID'];


                        // $sqlnv = "
                        //     SELECT nv.*, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc
                        //     FROM nhan_vien nv
                        //     JOIN phan_ca_lam pcl ON nv.Ma_nv = pcl.Ma_nv
                        //     JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
                        //     WHERE nv.ID_bophan = '$Idbp' AND pcl.ngay = CURDATE()
                        // ";
                        // $resultnv = $conn->query($sqlnv);

                        // if ($resultnv->num_rows > 0) {
                        //     $stt = 1; 
                        //     while ($row = $resultnv->fetch_assoc()) {
                        //         $statusIcon = $row['login'] == 1
                        //             ? '<i class="fas fa-circle" style="color: green;"></i>'
                        //             : '<i class="fas fa-circle" style="color: gray;"></i>';

                        //         echo "<tr>
                        //             <td>{$stt}</td>
                        //             <td>$statusIcon &nbsp;&nbsp;{$row['Hoten']}</td>
                        //             <td>{$row['Gioitinh']}</td>
                        //             <td>{$row['Tenca']}</td>
                        //             <td>{$row['Gio_bat_dau']}</td>
                        //             <td>{$row['Gio_ket_thuc']}</td>
                        //         </tr>";
                        //         $stt++; 
                        //     }
                        // } else {
                        //     echo "<tr><td colspan='6'>Không có nhân viên nào có ca làm hôm nay.</td></tr>";
                        // }

                        // $conn->close();
                        ?>
                    </tbody>
                </table>
                        </div>
                    </div>
                </div>
              
                    </div>
            
                            </section> -->

        <?php

        $currentMonth = date('m');
        $currentYear = date('Y');
        $username = $_SESSION['username'];
        $sqlpbt = "SELECT ID FROM bo_phan WHERE email = '$username'";
        $resultbp = mysqli_query($conn, $sqlpbt);
        if (isset($resultbp)) {
            $row1 = $resultbp->fetch_assoc();
            if ($row1) {
                $Idbp = $row1['ID'];
            }
        };

        $sql = "SELECT nv.Hoten, nv.Ma_nv,nv.Ma_bo,nv.Ma_nhom,nv.chucdanh, DATE(pcl.ngay) AS ngay, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc, pcl.id
        FROM nhan_vien nv
        JOIN phan_ca_lam pcl ON nv.Ma_nv = pcl.Ma_nv
        JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
        WHERE nv.ID_bophan = '$Idbp' 
          AND MONTH(pcl.ngay) = '$currentMonth' 
          AND YEAR(pcl.ngay) = '$currentYear'";

        $sql1 = "SELECT DATE(pcl.ngay) AS ngay, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc, pcl.id, pcl.Ma_nv
FROM phan_ca_lam pcl
JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
WHERE pcl.Ma_nv = '$username' 
  AND MONTH(pcl.ngay) = '$currentMonth' 
  AND YEAR(pcl.ngay) = '$currentYear'";

        $result2 = $conn->query($sql1);



        // Lấy số ngày trong tháng hiện tại
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $dates = range(1, $daysInMonth);

        $result1 = $conn->query($sql);
        $shifts = [];
        $colorMap = [];

        function getRandomColor()
        {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $hoten = $row1['Hoten'];
                $maNV = $row1['Ma_nv'];
                $maBo = $row1['Ma_bo'];
                $maNhom = $row1['Ma_nhom'];
                $chucDanh = $row1['chucdanh'];
                $ngay = date('j', strtotime($row1['ngay'])); // Chỉ lấy ngày
                $tenca = $row1['Tenca'];
                $gio_bat_dau = date('H:i', strtotime($row1['Gio_bat_dau']));
                $gio_ket_thuc = date('H:i', strtotime($row1['Gio_ket_thuc']));

                if (!isset($colorMap[$tenca])) {
                    $colorMap[$tenca] = getRandomColor(); // Gán màu ngẫu nhiên cho tên ca
                }

                // Lưu trữ dữ liệu ca làm việc vào mảng
                $shifts[$hoten][$ngay] = [
                    'tenca' => $tenca,
                    'gio_bat_dau' => $gio_bat_dau,
                    'gio_ket_thuc' => $gio_ket_thuc,
                    'maNV' => $maNV,
                    'maBo' => $maBo,
                    'maNhom' => $maNhom,
                    'chucDanh' => $chucDanh,

                ];
            }
        }


        ?>

        <style>
            table {
                width: 100%;
                /* Đặt chiều rộng bảng là 100% */
                table-layout: fixed;
                /* Tự động điều chỉnh chiều rộng các cột */
            }

            td {
                /* overflow: hidden; Ẩn phần thừa */
                text-overflow: ellipsis;
                /* Hiển thị dấu ba chấm nếu nội dung quá dài */
                white-space: nowrap;
                /* Không xuống dòng */
            }

            th.namenv {
                width: 20%;
                /* Chiều rộng cố định cho cột tên nhân viên */
            }

            th.manv {
                width: 18%;
                /* Chiều rộng cố định cho cột tên nhân viên */
            }

            th.namenv,
            th.thdate {
                background: #e9984c;
            }

            th {
                width: 8%;
                /* Đặt chiều rộng cho cột ngày */
            }

            td,
            th {
                text-align: center;
                /* Căn giữa nội dung trong ô */
            }

            td.tdname {
                white-space: normal;
                /* Cho phép nội dung xuống dòng trong cột tên */
            }
        </style>

        <section class="content">
            <div class="container-fluid">
                <p class="text-danger">Ca làm việc của team</p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2"
                                style="padding-left:5px;vertical-align: middle;background: lightgreen; width:8%">STT</th>
                            <th class="manv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Mã NV
                            </th>
                            <th class="namenv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Tên
                            </th>
                            <th class="manv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Mã bộ
                            </th>
                            <th class="manv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Nhóm
                            </th>
                            <th class="manv" rowspan="2" style="vertical-align: middle;background: lightgreen;">Chức danh
                            </th>
                            <?php foreach ($dates as $date): ?>
                                <th class="thdate"><?= $date ?></th>
                            <?php endforeach; ?>
                        </tr>

                        <tr>
                            <?php foreach ($dates as $date): ?>
                                <th style="padding-left: 2px" class="date thdate">
                                    <?= date('D', mktime(0, 0, 0, $currentMonth, $date, $currentYear)) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $s = 0 ?>

                        <?php foreach ($shifts as $hoten => $shiftsData): ?>
                            <tr>
                                <td><?php echo $s += 1; ?></td>
                                <td><?= $shiftsData[array_key_first($shiftsData)]['maNV'] ?? ''; ?></td>
                                <td class="tdname"><?= $hoten ?></td>
                                <td><?= $shiftsData[array_key_first($shiftsData)]['maBo'] ?? ''; ?></td>
                                <td><?= $shiftsData[array_key_first($shiftsData)]['maNhom'] ?? ''; ?></td>
                                <td><?= $shiftsData[array_key_first($shiftsData)]['chucDanh'] ?? ''; ?></td>


                                <?php foreach ($dates as $date): ?>



                                    <?php if (isset($shiftsData[$date])): ?>
                                        <?php
                                        $tenca = $shiftsData[$date]['tenca'];
                                        $color = $colorMap[$tenca]; // Lấy màu đã gán
                                        ?>
                                        <td style="background-color:<?= $color ?>;" class="font-weight-bold">
                                            <?= $shiftsData[$date]['tenca'] ?></td>
                                    <?php else: ?>
                                        <td class="text-secondary">K</td>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <p class="text-primary">Bảng Chú Thích</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Màu sắc</th>
                            <th>Tên ca làm</th>
                            <th>Giờ bắt đầu</th>
                            <th>Giờ kết thúc</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // Lặp lại để lấy giờ bắt đầu và kết thúc cho từng ca
                        $shiftTimes = [];
                        foreach ($shifts as $shiftsData) {
                            foreach ($shiftsData as $data) {
                                $tenca = $data['tenca'];
                                if (!isset($shiftTimes[$tenca])) {
                                    $shiftTimes[$tenca] = [
                                        'gio_bat_dau' => $data['gio_bat_dau'],
                                        'gio_ket_thuc' => $data['gio_ket_thuc'],
                                    ];
                                } else {
                                    // Nếu ca đã có, chỉ cần kiểm tra và cập nhật nếu cần
                                    $existingStart = $shiftTimes[$tenca]['gio_bat_dau'];
                                    $existingEnd = $shiftTimes[$tenca]['gio_ket_thuc'];
                                    if ($data['gio_bat_dau'] < $existingStart) {
                                        $shiftTimes[$tenca]['gio_bat_dau'] = $data['gio_bat_dau'];
                                    }
                                    if ($data['gio_ket_thuc'] > $existingEnd) {
                                        $shiftTimes[$tenca]['gio_ket_thuc'] = $data['gio_ket_thuc'];
                                    }
                                }
                            }
                        }
                        ?>

                        <?php foreach ($colorMap as $tenca => $color): ?>
                            <tr>
                                <td style="background-color: <?= $color ?>;"></td>
                                <td><?= $tenca ?></td>
                                <td><?= $shiftTimes[$tenca]['gio_bat_dau'] ?></td>
                                <td><?= $shiftTimes[$tenca]['gio_ket_thuc'] ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td style="background-color: #fff;"></td>
                            <td>K</td>
                            <td colspan="2">Không có ca làm việc</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </section>


    <?php } ?>

    <!-- /.content -->

</div>
<!-- /.content-wrapper -->
<script type="text/javascript" src="../Public/admin/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../Public/admin/plugins/chart.js/Chart.min.js"></script>
<script>
    $(document).ready(function() {
        showGraph();
    });

    function showGraph() {

        $.post("data.php",
            function(data) {
                console.log(data);
                var formStatusVar = [];
                var total = [];

                for (var i in data) {
                    formStatusVar.push(data[i].Tong);
                    total.push(data[i].Tong);
                }

                var options = {
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            display: true
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                };

                var myChart = {
                    labels: formStatusVar,
                    datasets: [{
                        label: 'Tổng số',
                        backgroundColor: '#17cbd1',
                        borderColor: '#46d5f1',
                        hoverBackgroundColor: '#0ec2b6',
                        hoverBorderColor: '#42f5ef',
                        data: total
                    }]
                };

                var bar = $("#graph");
                var barGraph = new Chart(bar, {
                    type: 'bar',
                    data: myChart,
                    options: options
                });
            });
    }
</script>
<?php require 'footer.php' ?>