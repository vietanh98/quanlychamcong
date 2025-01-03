<?php
require_once "../src/db.php";
global $conn;
$id = $_GET['x'];
$sql = " UPDATE nhan_luong SET Tinh_trang ='Đã thanh toán' where ID = '$id'";
$result = mysqli_query($conn, $sql);
if ($result == true) {
    header("Location:ql_bang_luong.php");
}
