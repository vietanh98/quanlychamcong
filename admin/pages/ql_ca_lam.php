<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$email = $_SESSION['username'];
$sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";
$resultbp = $conn->query($sqlpb);
$row1 = $resultbp->fetch_assoc();
if (isset($row1)) {

    $Idbp = $row1['ID'];
}

?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ca làm</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Ca làm</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <?php if ($_SESSION['role'] == 0) { ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <!-- <button onclick="window.location.href='add_ca_lam.php'" class="btn btn-outline-success">Thêm mới</button> -->
                                <!-- <h3 class="card-title">DataTable with minimal features & hover style</h3> -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-4">

                                <?php
                                $conn = mysqli_connect('localhost', 'root', 'Anh911998', 'quanlychamcong') or die('Không thể kết nối SQL');

                                // Truy vấn dữ liệu cho Thứ 2 - Thứ 7
                                $sql_weekdays = "SELECT Tenca, Gio_bat_dau, Gio_ket_thuc FROM ca_lam_viec WHERE Thu = '2-7' ORDER BY Gio_bat_dau";
                                $result_weekdays = mysqli_query($conn, $sql_weekdays);

                                // Truy vấn dữ liệu cho Chủ Nhật
                                $sql_sunday = "SELECT Tenca, Gio_bat_dau, Gio_ket_thuc FROM ca_lam_viec WHERE Thu = 'CN' ORDER BY Gio_bat_dau";
                                $result_sunday = mysqli_query($conn, $sql_sunday);

                                // Xử lý dữ liệu
                                $shifts_weekdays = [];
                                while ($row = mysqli_fetch_assoc($result_weekdays)) {
                                    $shifts_weekdays[$row['Tenca']][] = $row['Gio_bat_dau'] . ' - ' . $row['Gio_ket_thuc'];
                                }

                                $shifts_sunday = [];
                                while ($row = mysqli_fetch_assoc($result_sunday)) {
                                    $shifts_sunday[$row['Tenca']] = $row['Gio_bat_dau'] . ' - ' . $row['Gio_ket_thuc'];
                                }

                                // Hàm để trả về lớp CSS dựa trên tên ca
                                function getShiftClass($shift)
                                {
                                    switch ($shift) {
                                        case 'Ca1':
                                            return 'shift-ca1';
                                        case 'Ca1P':
                                            return 'shift-ca1p';
                                        case 'Ca2P':
                                            return 'shift-ca2p';
                                        case 'Ca2':
                                            return 'shift-ca2';
                                        case 'Ca3':
                                            return 'shift-ca3';
                                        case 'K':
                                            return 'shift-k';
                                        case 'OFF':
                                            return 'shift-off';
                                        default:
                                            return '';
                                    }
                                }
                                ?>

                                <style>
                                    .shift-ca1 {
                                        background-color: #b0c0f0;
                                    }

                                    /* Màu xanh dương nhạt cho ca S */
                                    .shift-ca1p {
                                        background-color: #6074d3;
                                    }

                                    /* Màu đỏ nhạt cho ca GS */
                                    .shift-ca2p {
                                        background-color: #467327;
                                    }

                                    /* Màu vàng nhạt cho ca T */
                                    .shift-ca2 {
                                        background-color: #9bc383;
                                    }

                                    /* Màu cam nhạt cho ca GC */
                                    .shift-ca3 {
                                        background-color: #6b4fa5;
                                    }

                                    /* Màu xanh lá nhạt cho ca Đ */
                                    .shift-k {
                                        background-color: #8a251a;
                                    }

                                    /* Màu xanh lá nhạt cho ca Đ */
                                    .shift-off {
                                        background-color: #e04131;
                                    }

                                    /* Màu xám cho ca K */
                                    .text-center {
                                        text-align: center;
                                    }
                                </style>

                                <table id="example2" class="table table-hover text-nowrap" border="1">
                                    <thead>
                                        <tr>
                                            <th>Ca</th>
                                            <th class="text-center" colspan="1">Thứ 2 - Thứ 7</th>
                                            <th class="text-center" colspan="1">CN</th>
                                        </tr>
                                        <!-- <tr>
            <th>9h</th>
            <th>10h</th>
            <th>11h</th>
            <th>12h</th>
        </tr> -->
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Hiển thị dữ liệu cho từng ca
                                        foreach ($shifts_weekdays as $tenca => $times) {
                                            echo "<tr>";

                                            // Gán lớp màu chỉ cho ô chứa tên ca
                                            $shiftClass = getShiftClass($tenca);
                                            echo "<td class='$shiftClass'><strong>$tenca</strong></td>";

                                            // Hiển thị các thời gian cho Thứ 2 - Thứ 7
                                            for ($i = 0; $i < 1; $i++) {
                                                echo "<td class='text-center'>" . (isset($times[$i]) ? $times[$i] : '') . "</td>";
                                            }
                                            // Hiển thị thời gian cho Chủ Nhật
                                            echo "<td class='text-center'>" . (isset($shifts_sunday[$tenca]) ? $shifts_sunday[$tenca] : '') . "</td>";

                                            echo "</tr>";
                                        }

                                        // Combine shift_k and shift_off into one row
                                        echo "<tr>";
                                        echo "<td class='shift-k'><strong>K</strong></td>";
                                        echo "<td class='text-center'colspan='1'>Nghỉ không lương</td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td class='shift-off'><strong>OFF</strong></td>";
                                        echo "<td class='text-center'colspan='1'>Nghỉ có lương</td>";
                                        echo "</tr>";
                                        ?>
                                    </tbody>
                                </table>




                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>

    <?php } ?>

    <?php if ($_SESSION['role'] == 1) { ?>

        <?php
        $currentMonth = date('m');
        $currentYear = date('Y');
        $username = $_SESSION['username'];


        $sql = "SELECT nv.Hoten,nv.Ma_nv,nv.Ma_bo,nv.Ma_nhom,nv.chucdanh, DATE(pcl.ngay) AS ngay, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc, pcl.id
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
                                    <?= date('D', mktime(0, 0, 0, $currentMonth, $date, $currentYear)) ?>
                                </th>
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
                                            <?= $shiftsData[$date]['tenca'] ?>
                                        </td>
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
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="row card-header" style="justify-content:space-between;">
                                <h4>Danh sách ca làm của nhân viên</h4>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <button onclick="window.location.href='phan_ca_lam.php'"
                                    class="btn btn-outline-success">Phân ca làm việc</button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table id="example2" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <!-- <th>STT</th> -->
                                            <th>Tên nhân viên</th>
                                            <th>Tên ca làm</th>
                                            <th>Giờ bắt đầu</th>
                                            <th>Giờ kết thúc</th>
                                            <th>Thời gian</th>
                                            <th>Ngày</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        // $sql = " select * from ca_lam_viec";  

                                        while ($row2 = mysqli_fetch_assoc($result2)) {

                                        ?>
                                            <tr>

                                                <td><?php echo $row2['Ma_nv']; ?></td>
                                                <td><?php echo $row2['Tenca']; ?></td>
                                                <td><?php echo $row2['Gio_bat_dau']; ?></td>
                                                <td><?php echo $row2['Gio_ket_thuc']; ?></td>
                                                <td><?php echo (strtotime($row2['Gio_ket_thuc']) - strtotime($row2['Gio_bat_dau'])) / 3600 . ' hours'; ?>
                                                </td>
                                                <td><?php echo $row2['ngay']; ?></td>
                                                <td>
                                                    <!-- <button class="btn btn-primary" onclick="window.location.href='edit_phan_ca.php?ID=<?php echo $row2['id'] ?>'"><i class="fa fa-edit"></i></button> -->
                                                    <button class="btn btn-danger"
                                                        onclick="if(confirm('Bạn có chắc chắn muốn xóa không?')) window.location.href='xoa_phan_ca.php?x=<?php echo $row2['id'] ?>'"><i
                                                            class="fas fa-trash"></i></button>
                                                </td>

                                            </tr>
                                        <?php } ?>


                                        <?php
                                        // $sql = " select * from ca_lam_viec";  
                                        $result = mysqli_query($conn, $sql);
                                        $s = 0;

                                        while ($row = mysqli_fetch_assoc($result)) {

                                        ?>
                                            <tr>
                                                <!-- <td><?php echo $s += 1; ?></td> -->
                                                <td><?php echo $row['Hoten']; ?></td>
                                                <td><?php echo $row['Tenca']; ?></td>
                                                <td><?php echo $row['Gio_bat_dau']; ?></td>
                                                <td><?php echo $row['Gio_ket_thuc']; ?></td>
                                                <td><?php echo (strtotime($row['Gio_ket_thuc']) - strtotime($row['Gio_bat_dau'])) / 3600 . ' hours'; ?>
                                                </td>
                                                <td><?php echo $row['ngay']; ?></td>
                                                <td>
                                                    <button class="btn btn-primary"
                                                        onclick="window.location.href='edit_phan_ca.php?ID=<?php echo $row['id'] ?>'"><i
                                                            class="fa fa-edit"></i></button>
                                                    <button class="btn btn-danger"
                                                        onclick="if(confirm('Bạn có chắc chắn muốn xóa không?')) window.location.href='xoa_phan_ca.php?x=<?php echo $row['id'] ?>'"><i
                                                            class="fas fa-trash"></i></button>
                                                </td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>

    <?php } ?>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require 'footer_ql.php' ?>