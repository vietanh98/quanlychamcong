<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec"); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thống kê theo năm </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                    </ol>
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
                            <button onclick="window.location.href='thong_ke_luong_thang.php'" class="btn btn-info">Xem theo tháng</button>
                            <div class="card-tools">
                                <form method="get" action="" style="display: flex;">
                                    <p style="margin: auto;padding-right: 10px;"> Năm</p>
                                    <select name="year" class="form-control" style="width: 100px;">
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                        <option value="2020">2020</option>
                                    </select>
                                    <input type="submit" name="search" value="Tìm kiếm" class="btn-primary" />
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Mã nhân viên</th>
                                        <th>Họ tên</th>
                                        <th>Số lương đã nhận</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $year = date('Y');
                                    if (isset($_GET['search'])) {
                                        $year = intval($_GET['year']);
                                    }
                                    echo "<h5>Năm $year</h5>";
                                    $sql = "SELECT nhan_vien.* FROM nhan_vien";
                                    $result = mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
                                    if ($result) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $manv = $row['Ma_nv'];
                                                require_once "../src/function.php";
                                                //Thưởng
                                                $sql2 = " SELECT sum(`Tong`) FROM `nhan_luong` WHERE Ma_nv = '$manv' and Tinh_trang = 'Đã thanh toán'  and YEAR(Thoi_gian) ='$year' ";
                                                $result2 = mysqli_query($conn, $sql2) or die("Câu truy vấn sai!");
                                                $luong_thang = mysqli_fetch_array($result2);
                                    ?>
                                                <tr>
                                                    <td><?php echo $row['Ma_nv']; ?></td>
                                                    <td><?php echo $row['Hoten']; ?></td>
                                                    <td><?php echo  number_format($luong_thang[0]) . ' đồng' ?></td>
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
                                        echo "ERROR: Không thể thực thi câu lệnh $sql. ";
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