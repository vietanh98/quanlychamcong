<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$manv = $_POST['Ma_nv'];
$today = date("Y/m/d");
$datetime = new DateTime();
$curtime = $datetime->format('H:i:s');
// var_dump($manv,$curtime);
require_once "../src/db.php";
global $conn;

$sql = "INSERT INTO cham_cong(Ma_nv, Ngay, Gio_checkin, Gio_checkout, Tinh_trang) VALUES('$manv','$today', '$curtime', NULL,'Đi làm')";
$result = mysqli_query($conn, $sql);
if ($result == true) {
    echo "<script> alert('Chấm công hôm nay thành công');</script>";
    echo "<script> window.location = 'home.php';</script>";
} else {
    echo "<script> alert('Có lỗi xảy ra: " . mysqli_error($conn) . "');</script>";
}
