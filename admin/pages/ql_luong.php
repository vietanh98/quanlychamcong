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
                    <h1>Hệ số lương</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Hệ số lương</li>
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
                            <button onclick="window.location.href='add_luong.php'" class="btn btn-outline-success">Thêm mới</button>
                            <!-- <h3 class="card-title">DataTable with minimal features & hover style</h3> -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Hệ số lương</th>
                                        <th>Lương cơ bản</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // $conn = mysqli_connect('localhost', 'root', "Anh911998", 'quanlychamcong') or die('không thể kết nối sql');
                                    $sql = "select * from luong";
                                    $result = mysqli_query($conn, $sql);
                                    $s = 0;
                                    // $result = mysqli_query($conn, "SELECT nhan_vien.*,bo_phan.tenbophan AS Ten FROM nhan_vien JOIN bo_phan ON nhan_vien.ID_bophan = bo_phan.ID;") or die("Câu lệnh truy vấn sai");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['He_so_luong']; ?></td>
                                            <td><?php echo  number_format($row['Luong_co_ban']), ' đồng '; ?></td>
                                            <td style="text-align: center;"><button class="btn btn-danger" onclick="window.location.href='xoa_luong.php?x=<?php echo $row['He_so_luong'] ?>'"><i class="fas fa-trash"></i></button></td>
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
<?php require 'footer_ql.php' ?>