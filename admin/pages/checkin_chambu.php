<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$manv = $_POST['Ma_nv'];
$today = date("Y/m/d");
$datetime = new DateTime();
$curtime = $datetime->format('H:i:s');
require_once "../src/db.php";
global $conn;

// Lấy giờ bắt đầu của ca làm việc
$sql_ca = "SELECT Gio_bat_dau FROM cham_bu WHERE Ma_nv = '$manv' AND Ngay_cham_bu = CURDATE() LIMIT 1";
$result_ca = mysqli_query($conn, $sql_ca);

if (mysqli_num_rows($result_ca) > 0) {
    $row_ca = mysqli_fetch_assoc($result_ca);
    $gio_bat_dau = $row_ca['Gio_bat_dau'];

    // So sánh giờ hiện tại với giờ bắt đầu
    if ($curtime < $gio_bat_dau) {
        echo "<script> alert('Chưa đến giờ chấm công, không thể check in!');</script>";
        echo "<script> window.location = 'home.php';</script>";
    } else {
        // Chấm công nếu giờ hiện tại đã đến giờ bắt đầu
        $sql = "INSERT INTO cham_cong(Ma_nv, Ngay, Gio_checkin, Gio_checkout, Tinh_trang, role) VALUES('$manv', '$today', '$curtime', NULL, 'Đi làm', 1)";
        $result = mysqli_query($conn, $sql);

        if ($result == true) {
            echo "<script> alert('Chấm công OT thành công');</script>";
            echo "<script> window.location = 'home.php';</script>";
        } else {
            echo "<script> alert('Có lỗi xảy ra: " . mysqli_error($conn) . "');</script>";
        }
    }
} else {
    echo "<script> alert('Không tìm thấy ca làm việc cho nhân viên này!');</script>";
    echo "<script> window.location = 'home.php';</script>";
}

// Đóng kết nối
mysqli_close($conn);
