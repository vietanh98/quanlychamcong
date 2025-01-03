<?php
require_once "../src/db.php";
global $conn;

if (isset($_POST['nhanvien']) && isset($_POST['phongban'])) {
    $nhanVienIds = json_decode($_POST['nhanvien']);
    $phongBanId = $_POST['phongban'];

    // Kiểm tra dữ liệu đầu vào
    if (is_array($nhanVienIds) && is_numeric($phongBanId)) {
        foreach ($nhanVienIds as $maNV) {
            // Kiểm tra và thực thi truy vấn
            $sql = "UPDATE nhan_vien SET ID_bophan = $phongBanId WHERE Ma_nv = '$maNV'";
            if (!mysqli_query($conn, $sql)) {
                // Nếu truy vấn thất bại, hiển thị lỗi chi tiết từ MySQL
                echo "Error updating record for Ma_nv $maNV: " . mysqli_error($conn);
                exit; // Thoát sau khi gặp lỗi đầu tiên
            }
        }
        echo "success";
    } else {
        echo "error: Invalid input data";
    }
} else {
    echo "error: Missing parameters";
}
