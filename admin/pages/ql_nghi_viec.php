<?php require '../layouts/header.php' ?>
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
                    <h1>Nghỉ việc</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Nghỉ việc</li>
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
                            <button onclick="window.location.href='add_nghi_viec.php'" class="btn btn-outline-success">Thêm mới</button>
                            <!-- <h3 class="card-title">DataTable with minimal features & hover style</h3> -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th>Ngày nghỉ</th>
                                        <th>Đến</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // $conn = mysqli_connect('localhost', 'root', "Anh911998", 'quanlychamcong') or die('không thể kết nối sql');
                                    $sql = " select * from nghi_viec";
                                    $result = mysqli_query($conn, "SELECT nghi_viec.*,nhan_vien.Hoten AS ten_nhan_vien FROM nghi_viec JOIN nhan_vien ON nghi_viec.Ma_nv = nhan_vien.Ma_nv;") or die("Câu lệnh truy vấn sai");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['ten_nhan_vien']; ?></td>
                                            <td><?php echo $row['Ngay_nghi']; ?></td>
                                            <td><?php echo $row['Nghi_den']; ?></td>
                                            <td><button class="btn btn-danger" onclick="window.location.href='xoa_nghi_viec.php?Manv=<?php echo $row['Ma_nv'] ?>'"> <i class="fas fa-trash"></i></button> </td>
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
<?php require '../layouts/footer_ql.php' ?>