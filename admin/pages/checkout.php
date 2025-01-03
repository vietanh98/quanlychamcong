<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$manv = $_POST['Ma_nv'];
$today = date("Y/m/d");
$datetime = new DateTime();
$curtime = $datetime->format('H:i:s');
// var_dump($manv,$curtime);
require_once "../src/db.php";
global $conn;
$nv_ca = $conn->query("SELECT clv.*
FROM phan_ca_lam pcl
JOIN ca_lam_viec clv ON pcl.id_calam = clv.ID
WHERE pcl.Ma_nv = '$manv' AND pcl.ngay = CURDATE()");

$sql_checkin = "SELECT Gio_checkin FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NULL";
$result_checkin = mysqli_query($conn, $sql_checkin);

if ($nv_ca && $row_ca = $nv_ca->fetch_assoc()) {
    $gio_bat_dau = $row_ca['Gio_bat_dau'];
    $gio_ket_thuc = $row_ca['Gio_ket_thuc'];

    $sql_checkin = "SELECT Gio_checkin FROM cham_cong WHERE Ma_nv = '$manv' AND Gio_checkout IS NULL";
    $result_checkin = mysqli_query($conn, $sql_checkin);

    if ($result_checkin && mysqli_num_rows($result_checkin) > 0) {
        $row = mysqli_fetch_assoc($result_checkin);
        $gio_checkin = $row['Gio_checkin'];

        $start_time = new DateTime($gio_checkin);
        $end_time = new DateTime($curtime);
        $interval = $start_time->diff($end_time);
        $so_gio_lam = $interval->h + ($interval->i / 60);

        // Tính số giờ theo ca làm việc
        $start_ca = new DateTime($gio_bat_dau);
        $end_ca = new DateTime($gio_ket_thuc);
        $interval_ca = $start_ca->diff($end_ca);
        $so_gio_ca = $interval_ca->h + ($interval_ca->i / 60);

        // Tính số giờ thiếu
        $so_gio_thieu = $so_gio_ca - $so_gio_lam;

        // Cập nhật vào CSDL
        $sql = "UPDATE cham_cong SET Gio_checkout = '$curtime', so_gio_lam = '$so_gio_lam', so_gio_thieu = '$so_gio_thieu' WHERE Ma_nv = '$manv' AND Gio_checkout IS NULL";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script> alert('Check out thành công');</script>";
            echo "<script> window.location = 'home.php';</script>";
        } else {
            echo "<script> alert('Có lỗi xảy ra: " . mysqli_error($conn) . "');</script>";
        }
    }
}
