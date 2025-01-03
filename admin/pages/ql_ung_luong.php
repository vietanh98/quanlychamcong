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
                    <h1>Ứng lương</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Ứng lương</li>
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
                            <button onclick="window.location.href='add_ung_luong.php'" class="btn btn-outline-success">Thêm mới</button>
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
                            <table id="example" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <!-- <th>Mã Nhân viên</th> -->
                                        <th>Nhân viên</th>
                                        <th>Số tiền</th>
                                        <th>Ngày ứng</th>
                                        <th>Hoạt động</th>
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
                                    $sql = "SELECT ung_luong.*,nhan_vien.Hoten AS ten_nhan_vien FROM ung_luong JOIN nhan_vien ON ung_luong.Ma_nv = nhan_vien.Ma_nv WHERE  MONTH(Ngay_ung) = '$month' and YEAR(Ngay_ung) ='$year';";
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    if ($result) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                                <tr>
                                                    <!-- <td><?php echo $row['Ma_nv']; ?></td> -->
                                                    <td><?php echo $row['ten_nhan_vien']; ?></td>
                                                    <td><?php echo number_format($row['So_tien']); ?><i> đồng</i></td>
                                                    <td> <?php echo $row['Ngay_ung'] ?></td>
                                                    <td> <button class="btn btn-danger" onclick="window.location.href='xoa_ung_luong.php?x=<?php echo $row['ID'] ?>'"><i class="fas fa-trash"></i></button></td>
                                                </tr>
                                            <?php } // Giải phóng bộ nhớ của biến
                                            mysqli_free_result($result);
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="4" style="text-align: center;">Không có dữ liệu trong bảng</td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "ERROR: Không thể thực thi câu lệnh $sql. ";
                                    }
                                    // Đóng kết nối
                                    // mysqli_close($sql);
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
<script>
    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")
</script>
<?php require 'footer_ql.php' ?>