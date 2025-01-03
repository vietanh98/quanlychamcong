<?php
$manv = $_GET['Manv'];
$hs = $_GET['Hs'];
$tong = $_GET['Tong'];
$days = $_GET['Day'];
require_once "../src/db.php";
global $conn;
//Số day đi làm
// $day = $conn->query("SELECT * FROM cham_cong where Ma_nv = '$manv'");
// $days = $day->num_rows;
//Thưởng
$sql2 = " SELECT sum(`So_tien`) FROM `thuong_phat` WHERE Ma_nv = '$manv' and Loai_hinh = 'Thưởng';";
$result2 = mysqli_query($conn, $sql2) or die("Có lỗi xảy ra: " . mysqli_error($conn));
$thuong = mysqli_fetch_array($result2);
// var_dump($thuong);
//Phạt
$sql3 = " SELECT sum(`So_tien`) FROM `thuong_phat` WHERE Ma_nv = '$manv' and Loai_hinh = 'Phạt';";
$result3 = mysqli_query($conn, $sql3) or die("Có lỗi xảy ra: " . mysqli_error($conn));
$phat = mysqli_fetch_array($result3);
//Ứng
$sql4 = " SELECT sum(`So_tien`) FROM `ung_luong` WHERE Ma_nv = '$manv';";
$result4 = mysqli_query($conn, $sql4) or die("Có lỗi xảy ra: " . mysqli_error($conn));
$ung = mysqli_fetch_array($result4);
$today = date("Y/m/d");
$thang = date('m');

$sql = " SELECT * FROM nhan_luong where  Ma_nv ='$manv' and MONTH(Thoi_gian) = '$thang' ";
$result = mysqli_query($conn, $sql);
$dem = mysqli_num_rows($result);
if ($dem > 0) {
    echo "<script> alert('Đã tính lương tháng này');</script>";
    echo "<script> window.location = 'ql_bang_luong.php';</script>";
    exit();
} else {
    $sql = "INSERT INTO nhan_luong values(ID,'$manv','$hs','$days','$thuong[0]','$phat[0]','$ung[0]','$tong',' $today','Chưa thanh toán')";
    $result = mysqli_query($conn, $sql) or die("Có lỗi xảy ra: " . mysqli_error($conn));
    // $sql2 = "DELETE FROM `thuong_phat` WHERE Ma_nv = '$manv'";
    // $sql3 = "DELETE FROM `ung_luong` WHERE Ma_nv = '$manv'";
    // $sql4 = "DELETE FROM `cham_cong` WHERE Ma_nv= '$manv'";
    // $result2 = mysqli_query($conn, $sql2) or die("câu lệnh truy vấn sai");
    // $result3 = mysqli_query($conn, $sql3) or die("câu lệnh truy vấn sai");
    // $result4 = mysqli_query($conn, $sql4) or die("câu lệnh truy vấn sai");
    if ($result == true) {
        // $result2;
        // $result3;
        // $result4;
        header("Location:ql_bang_luong.php");
    }
}                        

// $sql = " select * from cham_cong where Ngay='$today' and Ma_nv = '$manv'";
// $kq_con = mysqli_query($conn, $sql);
// $dem = mysqli_num_rows($kq_con);

// if ($dem > 0) {
//     echo "<script> alert('Đã chấm công hôm nay');</script>";
//     echo "<script> window.location = 'ql_cham_cong.php';</script>";
//     exit();
// } else {
//     $sql = "INSERT INTO cham_cong VALUES(ID_cham_cong,'$manv','$today','Đi làm')";
//     $result = mysqli_query($conn, $sql);
//     if ($result == true) {
//         header("Location:ql_cham_cong.php");
//     }
// }
