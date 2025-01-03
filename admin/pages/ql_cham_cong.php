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
                    <h1>Lịch sử chấm công</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Chấm công</li>
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
                            <div class="card-title"> <button onclick="window.location.href='add_cham_cong2.php'" class="btn btn-outline-success">Thêm mới</button></div>
                            <div class="card-tools">
                                <form action="" method="post">
                                    <div class="input-group">
                                        <input type="date" name="date" class="form-control float-right" />
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nhân viên</th>
                                        <th>Ngày</th>
                                        <th>Tình trạng</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //Kết nối máy chủ MySQL.
                                    $conn = mysqli_connect('localhost', 'root', "Anh911998", 'quanlychamcong') or die('không thể kết nối sql');
                                    $date = '%';
                                    if (isset($_POST['date'])) {
                                        $date = '%' . $_POST['date'] . '%';
                                    }
                                    // Thực hiện câu lệnh SELECT
                                    $sql = "SELECT cham_cong.*,nhan_vien.Hoten AS ten_nhan_vien FROM cham_cong JOIN nhan_vien ON cham_cong.Ma_nv = nhan_vien.Ma_nv WHERE Ngay LIKE '$date';";
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    if ($result) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                                <tr>
                                                    <td><?php echo $row['ID_cham_cong']; ?></td>
                                                    <td><?php echo $row['ten_nhan_vien']; ?></td>
                                                    <td><?php echo $row['Ngay']; ?></td>
                                                    <td><?php echo $row['Tinh_trang']; ?></td>
                                                    <!--  <button class="btn btn-warning" onclick="window.location.href='edit_cham_cong.php?x=<?php echo $row['ID_cham_cong'] ?>'">Nghỉ</button> -->
                                                    <td> <button class="btn btn-danger" onclick="window.location.href='xoa_cham_cong.php?x=<?php echo $row['ID_cham_cong'] ?>'"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            <?php }
                                            // Giải phóng bộ nhớ của biến
                                            mysqli_free_result($result);
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="5" style="text-align: center;">Không có dữ liệu trong bảng</td>
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
<?php require 'footer_ql.php' ?>