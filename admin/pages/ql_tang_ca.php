<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM cham_bu");
$email = $_SESSION['username'];
$sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";
$resultbp = $conn->query($sqlpb);
$row1 = $resultbp->fetch_assoc();
$Idbp = $row1['ID'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tăng ca</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tăng ca</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <?php
    $currentMonth = date('m');
    $currentYear = date('Y');

    $sql = "SELECT nv.Hoten, nv.Ma_nv, DATE(pcl.ngay) AS ngay, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc, pcl.id
        FROM nhan_vien nv
        JOIN phan_ca_lam pcl ON nv.Ma_nv = pcl.Ma_nv
        JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
        WHERE nv.ID_bophan = '$Idbp' 
          AND MONTH(pcl.ngay) = '$currentMonth' 
          AND YEAR(pcl.ngay) = '$currentYear'";

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
            $ma_nv = $row1['Ma_nv']; // Lưu mã nhân viên
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
                'ma_nv' => $ma_nv, // Lưu mã nhân viên
            ];
        }
    }

    // Lấy số giờ thiếu theo từng ngày từ bảng cham_cong
    $so_gio_thieu = [];
    $so_gio_thieu_sql = "SELECT Ma_nv, DAY(Ngay) AS ngay, SUM(so_gio_thieu) AS total_thieu 
                     FROM cham_cong 
                     WHERE MONTH(Ngay) = '$currentMonth' AND YEAR(Ngay) = '$currentYear' 
                     GROUP BY Ma_nv, DAY(Ngay)";
    $result_thieu = $conn->query($so_gio_thieu_sql);

    if ($result_thieu->num_rows > 0) {
        while ($row = $result_thieu->fetch_assoc()) {
            $so_gio_thieu[$row['Ma_nv']][$row['ngay']] = $row['total_thieu']; // Lưu số giờ thiếu theo từng ngày
        }
    }
    ?>

    <style>
        table {
            width: 100%;
            table-layout: fixed;
        }

        td {
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        th.namenv {
            width: 30%;
        }

        th.namenv,
        th.thdate {
            background: #e9984c;
        }

        th {
            width: 8%;
        }

        td,
        th {
            text-align: center;
        }

        td.tdname {
            white-space: normal;
        }
    </style>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- id="example1" -->
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã nhân viên</th>
                                        <th>Tên</th>
                                        <th>Thời gian ca</th>
                                        <th>Ngày</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $email = $_SESSION['username'];
                                    $sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";
                                    $resultbp = $conn->query($sqlpb);
                                    $row1 = $resultbp->fetch_assoc();
                                    $Idbp = $row1['ID'];

                                    // Câu SQL để lấy thông tin chấm công
                                    $sql = "
                                    SELECT nv.Ma_nv, nv.Hoten, tc.Gio_bat_dau, tc.Gio_ket_thuc, tc.Ngay, tc.status, tc.id
                                    FROM nhan_vien nv
                                    JOIN tang_ca tc ON nv.Ma_nv = tc.Ma_nv
                                    WHERE nv.ID_bophan = '$Idbp'
                                ";

                                    // Thực thi câu truy vấn
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");

                                    if ($result) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                                <tr>
                                                    <td><?php echo $row['Ma_nv']; ?></td>
                                                    <td><?php echo $row['Hoten']; ?></td>
                                                    <td><?php echo $row['Gio_bat_dau'] . ' - ' . $row['Gio_ket_thuc'] ?></td>
                                                    <td><?php echo $row['Ngay']; ?></td>
                                                    <?php $statusChecked = $row['status'] ? 'checked' : ''; ?>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox" <?php echo $statusChecked; ?> onchange="updateStatus('<?php echo $row['id']; ?>', this)">

                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>

                                                    <td>
                                                        <button class="btn btn-danger" onclick="if (confirm('Bạn có chắc chắn muốn xóa?')) window.location.href='xoa_tang_ca.php?x=<?php echo $row['id'] ?>'"><i class="fa-solid fa-trash"></i></button>
                                                    </td>

                                                </tr>
                                            <?php }
                                            mysqli_free_result($result);
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="6" style="text-align: center;">Không có dữ liệu trong bảng</td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "ERROR: Không thể thực thi câu lệnh $sql.";
                                    }

                                    if (isset($_POST['duyet']) && !empty($_POST['check_list'])) {
                                        foreach ($_POST['check_list'] as $id_cham_cong) {
                                            $update_sql = "UPDATE cham_cong SET phe_duyet = 1 WHERE ID_cham_cong = '$id_cham_cong'";
                                            mysqli_query($conn, $update_sql);
                                        }

                                        echo "<script>
                                        alert('Đã duyệt công thành công!');
                                        window.location.href = 'duyet_cham_cong.php';
                                      </script>";
                                        exit; // 
                                    }
                                    mysqli_close($conn);

                                    ?>

                                    <div class="mb-2">
                                        <a href="add_tang_ca.php" name="duyet" class="btn btn-success">Thêm mới</a>
                                    </div>
                                    </form>
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
<?php require 'footer_ql.php' ?>

<script>
    function updateStatus(id, switchElement) {
        const status = switchElement.checked ? 1 : 0; // Trạng thái 1 hoặc 0
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status_tc.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("Trạng thái đã được cập nhật.");
            }
        };
        xhr.send("id=" + id + "&status=" + status);
    }
</script>

<script>
    // Script để chọn/deselect tất cả các checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });
</script>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(14px);
    }
</style>