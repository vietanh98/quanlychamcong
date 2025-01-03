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
                    <h1>Lương nhân viên</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Lương</li>
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
                            <h3 class="card-title">Thông tin nhận lương</h3>
                            <div class="card-tools">
                                <form method="get" action="" style="display: flex;">
                                    <p style="margin: auto;padding-right: 10px;"> Năm</p>
                                    <select name="year" class="form-control" style="width: 100px;">
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                        <option value="2020">2020</option>
                                    </select>
                                    <p style="margin: auto;padding-right: 10px;padding-left: 10px;"> Tháng</p>
                                    <select name="month" class="form-control" style="width: 70px;">
                                        <option value="1"> 1</option>
                                        <option value="2"> 2</option>
                                        <option value="3"> 3</option>
                                        <option value="4"> 4</option>
                                        <option value="5"> 5</option>
                                        <option value="6"> 6</option>
                                        <option value="7"> 7</option>
                                        <option value="8"> 8</option>
                                        <option value="9"> 9</option>
                                        <option value="10"> 10</option>
                                        <option value="11"> 11</option>
                                        <option value="12"> 12</option>
                                    </select>
                                    <input type="submit" name="search" value="Tìm kiếm" class="btn-primary" />
                                </form>
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th>Lương cơ bản</th>
                                        <th>Số ngày làm</th>
                                        <th>Tiền thưởng</th>
                                        <th>Tiền phạt</th>
                                        <th>Tiền ứng</th>
                                        <th>Thực lĩnh</th>
                                        <th>Tháng nhận</th>
                                        <th>Tình Trạng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $year = date('Y');
                                    $month = date('m');
                                    if (isset($_GET['search'])) {
                                        $year = intval($_GET['year']);
                                        $month = intval($_GET['month']);
                                    }
                                    // Câu SQL lấy danh sách
                                    $sql = " SELECT nhan_luong.*,nhan_vien.Hoten,luong.Luong_co_ban FROM nhan_luong JOIN nhan_vien ON nhan_luong.Ma_nv = nhan_vien.Ma_nv JOIN luong ON nhan_luong.He_so_luong = luong.He_so_luong WHERE MONTH(Thoi_gian) = '$month' and YEAR(Thoi_gian) ='$year'";
                                    // Thực thi câu truy vấn và gán vào $result
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    if ($result) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                                <tr>
                                                    <td><?php echo $row['Hoten']; ?></td>
                                                    <td><?php echo  number_format($row['Luong_co_ban']) . ' đồng'; ?></td>
                                                    <td><?php echo $row['So_ngay_lam']; ?></td>
                                                    <td><?php echo $row['Tien_thuong']; ?></td>
                                                    <td><?php echo $row['Tien_phat']; ?></td>
                                                    <td><?php echo $row['Tien_ung']; ?></td>
                                                    <td><?php echo number_format($row['Tong']) . ' đồng'; ?></td>
                                                    <td><?php echo $row['Thoi_gian']; ?></td>
                                                    <td><button style=" border-radius: 0px 0px 40%;font-size:13px;" class="btn btn-outline-info" onclick="window.location.href='thanh_toan.php?x=<?php echo $row['ID'] ?>'">
                                                            <?php if ($row['Tinh_trang'] == 'Đã thanh toán') {
                                                                echo 'Đã thanh toán';
                                                            } else {
                                                                echo 'Chưa thanh toán';
                                                            } ?>
                                                        </button></td>
                                                </tr>
                                            <?php }  // Giải phóng bộ nhớ của biến
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