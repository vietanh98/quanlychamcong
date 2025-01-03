<?php
require_once "../src/db.php";
global $conn;
$manv = $_GET['x'];
$sql1 = "DELETE from phan_ca_lam where Ma_nv='$manv'";
mysqli_query($conn, $sql1) or die("Đã xảy ra lỗi!");

$sql2 = "DELETE from cham_cong where Ma_nv='$manv'";
mysqli_query($conn, $sql2) or die("Đã xảy ra lỗi!");

$sql3 = "DELETE from  nhan_luong where Ma_nv='$manv'";
mysqli_query($conn, $sql3) or die("Đã xảy ra lỗi!");

$sql4 = "DELETE from  thuong_phat where Ma_nv='$manv'";
mysqli_query($conn, $sql4) or die("Đã xảy ra lỗi!");

$sql5 = "DELETE from  ung_luong where Ma_nv='$manv'";
mysqli_query($conn, $sql5) or die("Đã xảy ra lỗi!");

$sql = "DELETE from nhan_vien where Ma_nv='$manv'";
$result = mysqli_query($conn, $sql) or die("Đã xảy ra lỗi!");
if ($result == true) {
     header("Location:ql_nhan_vien.php");
}
