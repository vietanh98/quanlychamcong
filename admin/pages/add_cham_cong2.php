<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chấm công</h1>
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
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Ngày <?php $today = date("d/m/Y");
                                    echo $today; ?>
                            <!-- <h3 class="card-title">DataTable with minimal features & hover style</h3> -->
                            <button class="btn btn-dark float-right" onclick="window.location.href='cham_cong_2.php'">Chấm công toàn bộ</button>
                            <button class="btn btn-info float-right" onclick="window.location.href='add_cham_cong.php'">Chấm công theo ngày</button>
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Mã nhân viên</th>
                                        <th>Chấm công</th>
                                    </tr>
                                </thead>
                                <?php
                                $sql = "SELECT * FROM nhan_vien";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tbody>
                                        <tr>
                                            <td> <?php echo $row['Hoten']; ?></td>
                                            <td> <button class="btn btn-info" onclick="window.location.href='cham_cong.php?Ma_nv=<?php echo $row['Ma_nv'] ?>&&Ngay=<?php echo $today ?>'">Chấm công</button></td>
                                        </tr>
                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->
<?php require 'footer_ql.php' ?>