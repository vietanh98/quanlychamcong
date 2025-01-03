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

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- <div class="card-header">
                            <h3 class="card-title">Thông tin chấm công của nhân viên</h3>
                            <div class="card-tools">
                            <form method="post" action="">
                                    <button type="submit" name="duyet" class="btn btn-success">Duyệt công</button>
                                
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

                        </div> -->
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="">
                                <!-- id="example1" -->
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Nhân viên</th>
                                            <th>Tên ca</th>
                                            <th>Thời gian ca</th>
                                            <th>Giờ checkin-checkout</th>
                                            <!-- <th>Giờ checkout</th> -->
                                            <th>Số giờ làm</th>
                                            <th>Số giờ thiếu</th>
                                            <th>Ca làm</th>
                                            <th>Ngày</th>
                                            <th>Trạng thái</th>
                                            <!-- <th>Thực lĩnh</th>
                                        <th>Tháng nhận</th>
                                        <th>Tình Trạng</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $email = $_SESSION['username'];
                                        $sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";
                                        $resultbp = $conn->query($sqlpb);
                                        $row1 = $resultbp->fetch_assoc();
                                        $Idbp = $row1['ID'];

                                        // Câu SQL để lấy thông tin chấm công
                                        $sql = "
                                    SELECT nv.Hoten, cc.Gio_checkin, cc.so_gio_lam, cc.so_gio_thieu, cc.ID_cham_cong, cc.phe_duyet, cc.Gio_checkout, cc.Ngay, cc.role, pcl.ngay, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc
                                    FROM nhan_vien nv
                                    JOIN phan_ca_lam pcl ON nv.Ma_nv = pcl.Ma_nv
                                    JOIN cham_cong cc ON cc.Ma_nv = nv.Ma_nv AND cc.Ngay = pcl.ngay
                                    JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
                                    WHERE nv.ID_bophan = '$Idbp'
                                ";

                                        // Thực thi câu truy vấn
                                        $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");

                                        if ($result) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                    <tr>
                                                        <td><input type="checkbox" name="check_list[]" value="<?php echo $row['ID_cham_cong']; ?>"></td>
                                                        <td><?php echo $row['Hoten']; ?></td>
                                                        <td><?php echo $row['Tenca']; ?></td>
                                                        <td><?php echo $row['Gio_bat_dau'] . ' - ' . $row['Gio_ket_thuc'] ?></td>
                                                        <td><?php echo $row['Gio_checkin'] . ' - ' . $row['Gio_checkout'] ?></td>
                                                        <td><?php echo $row['so_gio_lam']; ?> h</td>
                                                        <td><?php echo $row['so_gio_thieu']; ?> h</td>
                                                        <td>
                                                            <?php if ($row['role'] == 0) { ?>
                                                                <span class="btn btn-primary">Thường</span>
                                                            <?php } else if ($row['role'] == 1) { ?>
                                                                <span class="btn btn-danger">Bù OT</span>

                                                            <?php } else { ?>
                                                                <span class="btn btn-success">Tăng ca</span>
                                                            <?php } ?>

                                                        </td>
                                                        <td><?php echo $row['Ngay']; ?></td>
                                                        <td><?php
                                                            $p = $row['phe_duyet'];
                                                            if ($p == 0) {
                                                                echo "<span class='text-danger'>Chưa duyệt</span>";
                                                            } else {
                                                                echo "<span class='text-success'>Đã duyệt</span>";
                                                            }

                                                            ?></td>
                                                    </tr>
                                                <?php }
                                                mysqli_free_result($result);
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center;">Không có dữ liệu trong bảng</td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "ERROR: Không thể thực thi câu lệnh $sql.";
                                        }

                                        if (isset($_POST['duyet']) && !empty($_POST['check_list'])) {
                                            foreach ($_POST['check_list'] as $id_cham_cong) {
                                                $update_sql = "UPDATE cham_cong SET phe_duyet = 1 WHERE ID_cham_cong = '$id_cham_cong'";
                                                mysqli_query($conn, $update_sql);
                                            }

                                            echo "<script>
                                        alert('Đã duyệt công thành công!');
                                        window.location.href = 'duyet_cham_cong.php';
                                      </script>";
                                            exit; // 
                                        }
                                        mysqli_close($conn);

                                        ?>

                                        <div class="mb-2">
                                            <button type="submit" name="duyet" class="btn btn-success">Duyệt công</button>
                                        </div>
                            </form>
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

<script>
    // Script để chọn/deselect tất cả các checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });
</script>