<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thống kê theo năm</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button onclick="window.location.href='thong_ke.php'" class="btn btn-info">Xem theo tháng</button>
                            <div class="card-tools">
                                <form method="get" action="" style="display: flex;">
                                    <p style="margin: auto;padding-right: 10px;"> Năm</p>
                                    <select name="year" class="form-control" style="width: 100px;">
                                        <?php
                                        $currentYear = date("Y"); // Lấy năm hiện tại
                                        for ($i = $currentYear - 50; $i <= $currentYear + 50; $i++) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="submit" name="search" value="Tìm kiếm" class="btn-primary" />
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Mã nhân viên</th>
                                        <th>Họ tên</th>
                                        <th>Mã phòng ban</th>
                                        <th>Tên phòng ban</th>
                                        <th>Tên bộ phận</th>
                                        <th>Số ngày đi làm</th>
                                        <th>Số giờ đã làm</th>
                                        <th>Số giờ thiếu</th>
                                        <th>Số giờ tăng ca</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $year = date('Y');
                                    if (isset($_GET['search'])) {
                                        $year = intval($_GET['year']);
                                    }
                                    echo "<h5>Năm $year</h5>";
                                    $email = $_SESSION['username'];
                                    $sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";

                                    $resultbp = $conn->query($sqlpb);
                                    $row1 = $resultbp->fetch_assoc();
                                    if (mysqli_num_rows($resultbp) > 0) {
                                        $Idbp = $row1['ID'];
                                        $sql = "SELECT nv.Ma_nv, nv.Hoten, bp.Ten, bp.ma_pb, bp.ten_bp
                                                FROM nhan_vien nv
                                                JOIN bo_phan bp ON nv.ID_bophan = bp.ID
                                                WHERE bp.ID = '$Idbp'";
                                    } else {
                                        $sql = "SELECT nv.Ma_nv, nv.Hoten, bp.Ten, bp.ma_pb, bp.ten_bp, nv.*
                                        FROM nhan_vien nv
                                        JOIN bo_phan bp ON nv.ID_bophan = bp.ID";
                                    }
                                    $result = mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
                                    if ($result) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $manv = $row['Ma_nv'];

                                                // Số ngày đi làm trong năm
                                                $day2 = $conn->query("SELECT * FROM cham_cong WHERE Ma_nv = '$manv' AND YEAR(Ngay) = '$year'");
                                                $day2s = $day2->num_rows;

                                                // Tổng số giờ làm trong năm và số giờ thiếu
                                                $sql_hours = "SELECT SUM(so_gio_lam) AS total_hours, SUM(so_gio_thieu) AS total_missing_hours 
                                                              FROM cham_cong 
                                                              WHERE Ma_nv = '$manv' AND YEAR(Ngay) = '$year'";
                                                $result_hours = $conn->query($sql_hours);
                                                $hours_row = $result_hours->fetch_assoc();
                                                $total_hours = $hours_row['total_hours'] ?? 0;
                                                $total_missing_hours = $hours_row['total_missing_hours'] ?? 0;

                                                // Tổng số giờ tăng ca trong năm
                                                $sql_overtime = "SELECT SUM(TIMESTAMPDIFF(HOUR, Gio_bat_dau, Gio_ket_thuc)) AS total_overtime 
                                                                 FROM tang_ca 
                                                                 WHERE Ma_nv = '$manv' AND YEAR(Ngay) = '$year'";
                                                $result_overtime = $conn->query($sql_overtime);
                                                $overtime_row = $result_overtime->fetch_assoc();
                                                $total_overtime = $overtime_row['total_overtime'] ?? 0;
                                    ?>
                                                <tr>
                                                    <td><?php echo $row['Ma_nv']; ?></td>
                                                    <td><?php echo $row['Hoten']; ?></td>
                                                    <td><?php echo $row['ma_pb']; ?></td>
                                                    <td><?php echo $row['Ten']; ?></td>
                                                    <td><?php echo $row['ten_bp']; ?></td>
                                                    <td><?php echo $day2s ?></td>
                                                    <td><?php echo $total_hours . ' giờ'; ?></td>
                                                    <td><?php echo $total_missing_hours . ' giờ'; ?></td>
                                                    <td><?php echo $total_overtime . ' giờ'; ?></td>
                                                </tr>
                                            <?php }
                                            mysqli_free_result($result);
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="10" style="text-align: center;">Không có dữ liệu trong bảng</td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "ERROR: Không thể thực thi câu lệnh $sql.";
                                    }
                                    // Đóng kết nối
                                    mysqli_close($conn);
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
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require 'footer_ql.php' ?>