<?php require 'table.html' ?>
<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$user_id = $_SESSION['id'];
$sqlpb = "SELECT nv.ID_bophan 
FROM nhan_vien nv
JOIN users u ON u.email = nv.username
WHERE u.id = $user_id";
$resultbp = $conn->query($sqlpb);
$row1 = $resultbp->fetch_assoc();
if (isset($row1)) {

    $Idbp = $row1['ID_bophan'];
}
$sqlnv = "SELECT * FROM nhan_vien WHERE ID_bophan = '$Idbp'";
$nhan_vien = $conn->query($sqlnv);
$sqlcalam = "SELECT * FROM ca_lam_viec";
$ca_lam = $conn->query($sqlcalam);

$id = $_GET['ID'];

$sqlcl = "SELECT * FROM phan_ca_lam WHERE ID = '$id'";
$resultcl = mysqli_query($conn, $sqlcl);
$phan_ca_lam = mysqli_fetch_assoc($resultcl);


$sql = "SELECT pcl.*, nv.Hoten, clv.ID, clv.Tenca, clv.Gio_bat_dau, clv.Gio_ket_thuc, pcl.ngay
        FROM phan_ca_lam pcl
        JOIN nhan_vien nv ON pcl.Ma_nv = nv.Ma_nv
        JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
        WHERE pcl.id = '$id'";

$result = mysqli_query($conn, $sql);
$row1 = mysqli_fetch_assoc($result);


?>
<div class="content-wrapper" style="min-height: 353px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Phân ca làm</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Phân ca làm</a></li>
                        <li class="breadcrumb-item active">Sửa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Sửa ca làm</h3>
                </div>
                <form method="post">
                    <div class="card-body">

                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Tên nhân viên</label>
                                    <select class="form-control" name="employee" id="">
                                        <option value="<?php echo $row1['Ma_nv']; ?>"><?php echo ($row1['Hoten']) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ca làm việc</label>
                                    <select class="form-control" name="ca_lam" id="">
                                        <option value="<?php echo $row1['ID']; ?>"><?php echo ($row1['Tenca'] . ' - ' . $row1['Gio_bat_dau'] . ' - ' . $row1['Gio_ket_thuc']) ?></option>
                                        <?php
                                        if ($ca_lam->num_rows > 0) {
                                            while ($row = $ca_lam->fetch_assoc()) {
                                                echo '<option value="' . $row['ID'] . '">' . $row['Tenca'] . ' - ' . $row['Gio_bat_dau'] . ' - ' . $row['Gio_ket_thuc'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ngày làm việc</label>
                                    <input value="<?php echo $row1['ngay']; ?>" type="date" name='ngay_lam' required class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="btn" class="btn btn-primary">Sửa</button>
                    </div>

                    <?php
                    if (isset($_POST['btn'])) {
                        $id_nv = $_POST['employee'];
                        $id_calam = $_POST['ca_lam'];
                        $ngay = $_POST['ngay_lam'];
                        $id = $_GET['ID'];
                        $sql2 = "UPDATE phan_ca_lam SET Ma_nv ='$id_nv', id_calam = '$id_calam', ngay ='$ngay' WHERE id='$id'";
                        $result2 = mysqli_query($conn, $sql2);
                        if ($result2 == true) {
                            echo "Sửa Thành Công !Hãy vào <a href='sua_lich_lam.php'>Danh sách </a> để xem lại";
                        } else {
                            echo "<script> alert('Có lỗi xảy ra: " . mysqli_error($conn) . "');</script>";
                        }

                        // $sql = "INSERT INTO ca_lam_viec VALUES(ID,'$start','$end','$employee')";
                        //     $result = mysqli_query($conn, $sql);
                        //     if ($result == true) {
                        //         echo "Thêm Thành Công !Hãy vào <a href='ql_ca_lam.php'>Danh sách </a> để xem lại";
                        //     }
                    }
                    ?>
                </form>
            </div>
    </section>
</div>
<?php require 'footer.php' ?>