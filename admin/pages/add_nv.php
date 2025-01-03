<?php require 'header.php' ?>
<?php require 'table.html' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$luong = $conn->query("SELECT * FROM luong"); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Thêm nhân viên</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Nhân viên</a></li>
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
                    <h3 class="card-title">Thêm nhân viên</h3>

                </div>
                <form method="post">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mã nhân viên</label>
                                    <input type="text" name="manv" class="form-control" placeholder="Mã nhân viên" required value="NV">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Tên nhân viên</label>
                                    <input type="text" name="ten" class="form-control" required placeholder="Tên nhân viên">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Giới tính</label><br>
                                    <input type="radio" name="gioitinh" value="Nam" checked="checked">
                                    <label>Nam</label>
                                    <input type="radio" name="gioitinh" value="Nữ">
                                    <label>Nữ</label>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Phòng ban</label>
                                    <select name="bophan" id="bophan" required class="form-control">
                                        <?php while ($row = $bo_phan->fetch_assoc()) : ?>
                                            <option value="<?php echo $row['ID'] ?>"><?php echo $row['Ten'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Chức danh</label>
                                    <input type="text" name="chucdanh" class="form-control" placeholder="Chức danh" required value="">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mã bộ</label>
                                    <input type="text" name="mabo" class="form-control" placeholder="Mã bộ" required value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mã nhóm</label>
                                    <input type="text" name="manhom" class="form-control" placeholder="Mã nhóm" required value="">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row mb-2"> -->
                        <!-- 
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Lương cơ bản</label>
                                    <select name="luong" id="luong" required class="form-control">
                                        <?php while ($row = $luong->fetch_assoc()) : ?>
                                            <option value="<?php echo $row['He_so_luong'] ?>"><?php echo $row['Luong_co_ban'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                            </div>
                            -->
                        <!-- <div class="col-sm-6"> -->
                        <div class="row mb-2">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email đăng nhập</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
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
                            <!-- </div> -->

                            <!-- </div> -->
                        </div>


                        <button type="submit" name="btn" class="btn btn-primary">Thêm</button>
                    </div>
                    <?php
                    if (isset($_POST['btn'])) {
                        $ma = $_POST['manv'];
                        $ten = $_POST['ten'];
                        $gt = $_POST['gioitinh'];
                        $chucdanh = $_POST['chucdanh'];
                        // $sdt = $_POST['sdt'];
                        $bophan = $_POST['bophan'];
                        $mabo = $_POST['mabo'];
                        $manhom = $_POST['manhom'];
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        // $luong = $_POST['luong'];
                        $status = 1;
                        $login = 0;
                        $luong = 3;
                        // if ($bophan == 1) {
                        //     $luong = 1;
                        // } elseif ($bophan == 3) {
                        //     if ($calam == 1 or $calam == 2) {
                        //         $luong = 2;
                        //     } else {
                        //         $luong = 4;
                        //     }
                        // } else {
                        //     if ($calam == 1 or $calam == 2) {
                        //         $luong = 3;
                        //     } else {
                        //         $luong = 4;
                        //     }
                        // }
                        // Câu SQL lấy danh sách
                        $sql = " select * from nhan_vien where Ma_nv='$ma'";
                        // Thực thi câu truy vấn và gán vào $result
                        $result = mysqli_query($conn, $sql);
                        // Kiểm tra số lượng record trả về có lơn hơn 0
                        // Nếu lớn hơn tức là có kết quả, ngược lại sẽ không có kết quả
                        $dem = mysqli_num_rows($result);
                        if ($dem > 0) {
                            echo "Mã Nhân Viên Đã Tồn Tại";
                            exit();
                        } else {
                            $sql = "INSERT INTO nhan_vien(Ma_nv, Hoten, Gioitinh,  chucdanh, ID_bophan, He_so_luong, Ma_bo, Ma_nhom, username, pass, status, login) VALUES('$ma','$ten','$gt', '$chucdanh', '$bophan','$luong','$mabo', '$manhom', '$email','$password', '$status', '$login')";
                            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                            $sql1 = "INSERT INTO users(username, password, role, active_message ) VALUES('$email','$password',2,0)";
                            $result1 = mysqli_query($conn, $sql1);
                            if ($result == true && $result1 == true) {
                                echo "Thêm Thành Công !Hãy vào <a href='ql_nhan_vien.php'>Danh sách nhân viên </a> để xem lại";
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