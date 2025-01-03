<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan"); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản lý phòng ban</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                        <li class="breadcrumb-item active">Quản lý phòng ban</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <?php if ($_SESSION['role'] == 0) { ?>
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Quản lý phòng ban</h3>
                    </div>
                    <form method="post">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tên phòng ban</label>
                                        <input type="text" name='ten' required class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mã phòng ban</label>
                                        <input type="text" name='ma_pb' required class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Bộ phận</label>
                                        <input type="text" name='ten_bp' required class="form-control">
                                    </div>
                                </div>
                                <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <input type="password" name='password' required class="form-control">
                                </div>
                            </div> -->
                            </div>

                            <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['btn'])) {
                        $ten = $_POST['ten'];
                        $ma_pb = $_POST['ma_pb'];
                        $ten_bp = $_POST['ten_bp'];
                        $sql = " select * from bo_phan where Ten = '$ten'";
                        $result = mysqli_query($conn, $sql);
                        $dem = mysqli_num_rows($result);
                        if ($dem > 0) {
                            echo "Tồn tại";
                            // exit();
                        } else {
                            $sql = "INSERT INTO bo_phan(Ten, ma_pb, ten_bp) VALUES('$ten', '$ma_pb','$ten_bp')";
                            $result = mysqli_query($conn, $sql);
                            // $sql1 = "INSERT INTO users(username, password, role) VALUES('$email','$pass',1)";
                            // $result1 = mysqli_query($conn, $sql1);
                            if ($result == true) {
                                echo "Thêm phòng ban thành công";
                            }
                        }
                    }
                    ?>
                </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <!-- <button onclick="window.location.href='add_bo_phan.php'" class="btn btn-outline-success">Thêm mới</button> -->


                                </div>



                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover " style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Mã phòng ban</th>
                                            <th>Tên phòng ban</th>
                                            <th>Tên bộ phận</th>
                                            <th>Số lượng nhân viên</th>
                                            <th>Hành động</th>
                                            <th>Danh sách nhân viên của phòng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        ?>
                                        <?php
                                        $sql = " select * from bo_phan";
                                        $s = 0;
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $manv = $row['ID'];
                                            require_once "../src/function.php";
                                            $nv = $conn->query("SELECT * FROM nhan_vien WHERE ID_bophan = $manv");
                                            $soluongnv = $nv->num_rows;
                                        ?>
                                            <tr>
                                                <td><?php echo $s += 1; ?></td>
                                                <td><?php echo $row['ma_pb']; ?></td>
                                                <td><?php echo $row['Ten']; ?></td>
                                                <td><?php echo $row['ten_bp']; ?></td>
                                                <td><?php echo $soluongnv; ?></td>

                                                <td>
                                                    <button class="btn btn-primary" onclick="window.location.href='edit_bo_phan.php?ID=<?php echo $row['ID'] ?>'"><i class="fa fa-edit"></i></button>
                                                    <button class="btn btn-danger" onclick="window.location.href='xoa_bo_phan.php?x=<?php echo $row['ID'] ?>'"><i class="fas fa-trash"></i></button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-success" onclick="window.location.href='ds_nv_pb.php?ID=<?php echo $row['ID'] ?>'"><i class="fa fa-user"></i></button>
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
    <?php } else { ?>
        <h5 class="text-danger pl-3">Bạn không có quyền truy cập</h5>
    <?php } ?>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require 'footer_ql.php' ?>