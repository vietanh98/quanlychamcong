<?php
// $manv = $_GET['Ma_nv'];
// $date = $_GET['Ngay'];
$today = date("Y/m/d");
require_once "../src/db.php";
global $conn;
$sql = " select * from cham_cong where Ngay='$today'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if ($count > 0) {
    echo "<script> alert('Đã chấm công hôm nay');</script>";
    echo "<script> window.location = 'ql_cham_cong.php';</script>";
    exit();
} else {
    $a = 0;
    $manv = 'NV0' . $a;
    require_once "../src/function.php";
    $nv = $conn->query("SELECT * FROM nhan_vien");
    $soluongnv = $nv->num_rows;
    while ($a < $soluongnv) {
        $manv++;
        $sql = "INSERT INTO cham_cong VALUES(ID_cham_cong,'$manv','$today','Đi làm')";
        $result = mysqli_query($conn, $sql);
        if ($result == true) {
            header("Location:ql_cham_cong.php");
        }
    }
}
