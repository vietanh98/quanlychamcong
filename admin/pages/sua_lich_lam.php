<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$user_id = $_SESSION['id'];
$sqlpb = "SELECT nv.ID_bophan 
FROM nhan_vien nv
JOIN users u ON u.email = nv.username
WHERE u.id = $user_id";
$resultbp = $conn->query($sqlpb);
$row1 = $resultbp->fetch_assoc();
if (isset($row1)) {

    $Idbp = $row1['ID_bophan'];
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




    <?php
    $currentMonth = date('m');
    $currentYear = date('Y');

    $sql = "SELECT nv.Hoten, DATE(pcl.ngay) AS ngay, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc, pcl.id
        FROM nhan_vien nv
        JOIN phan_ca_lam pcl ON nv.Ma_nv = pcl.Ma_nv
        JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
        WHERE nv.ID_bophan = '$Idbp' 
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
            width: 30%;
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
                            <!-- <button onclick="window.location.href='phan_ca_lam.php'" class="btn btn-outline-success">Phân ca làm việc</button> -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>STT</th>
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
                                    $result = mysqli_query($conn, $sql);
                                    $s = 0;

                                    while ($row = mysqli_fetch_assoc($result)) {

                                    ?>
                                        <tr>
                                            <td><?php echo $s += 1; ?></td>
                                            <td><?php echo $row['Hoten']; ?></td>
                                            <td><?php echo $row['Tenca']; ?></td>
                                            <td><?php echo $row['Gio_bat_dau']; ?></td>
                                            <td><?php echo $row['Gio_ket_thuc']; ?></td>
                                            <td><?php echo (strtotime($row['Gio_ket_thuc']) - strtotime($row['Gio_bat_dau'])) / 3600 . ' hours'; ?></td>
                                            <td><?php echo $row['ngay']; ?></td>
                                            <td>
                                                <button class="btn btn-primary" onclick="window.location.href='edit_phan_ca_nv.php?ID=<?php echo $row['id'] ?>'"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa không?')) window.location.href='xoa_phan_ca.php?xnv=<?php echo $row['id'] ?>'"><i class="fas fa-trash"></i></button>
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

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require 'footer.php' ?>