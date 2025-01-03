<?php
require_once "../src/db.php";
global $conn;
session_start();
unset($_SESSION['username']);
if (isset($_SESSION['Ma_nv'])) {
	$user = $_SESSION['Ma_nv'];
	$sql = " UPDATE nhan_vien SET login = 0 WHERE Ma_nv='$user'";
	$result = mysqli_query($conn, $sql);
}
echo "<script> alert('Bạn đã đăng xuất khỏi hệ thống');</script>";
echo "<script> window.location = 'index.php';</script>";
