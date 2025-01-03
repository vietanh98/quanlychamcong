<?php require 'header.php' ?>
<?php require 'table.html' ?>
<?php require_once "../src/db.php";
global $conn;
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Tài khoản admin</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Thêm</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Thêm tài khoản</h3>

                </div>
                <form method="post">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Tên đăng nhập</label>
                                    <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" required value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <button type="submit" name="btn" class="btn btn-success w-100">Tạo tài khoản</button>

                        </div>



                        <!-- </div> -->

                        <!-- </div> -->



                    </div>
                    <?php
                    if (isset($_POST['btn'])) {
                        $username = $_POST['username'];
                        $password = $_POST['password'];

                        $sql = " select * from users where username='$username'";

                        $result = mysqli_query($conn, $sql);

                        $dem = mysqli_num_rows($result);
                        if ($dem > 0) {
                            echo "<span class='text-danger pl-3'>Username Đã Tồn Tại</span>";
                            exit();
                        } else {
                            $sql1 = "INSERT INTO users VALUES(id,'$username', NULL, '$password',0,1)";
                            $result1 = mysqli_query($conn, $sql1);
                            if ($result1 == true) {
                                echo "<span class='text-success pl-3'>Tạo tài khoản thành công</span>";
                            } else {
                                echo "<script> alert('Có lỗi xảy ra: " . mysqli_error($conn) . "');</script>";
                            }
                        }
                    }
                    ?>
                </form>

            </div>
    </section>
</div>

<?php require 'footer_ql.php' ?>