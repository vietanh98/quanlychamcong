<?php
session_start();
require_once "../src/db.php";
global $conn;


// Nhận dữ liệu từ yêu cầu POST
$id = $_GET['id'];


// Cập nhật trường assigned_to trong bảng chat
$stmt = $conn->prepare("DELETE FROM chat WHERE id = $id");

if ($stmt->execute()) {
    // Nếu cập nhật thành công, gửi thông báo
    echo json_encode(['success' => true]);
} else {
    // Nếu có lỗi, gửi thông báo lỗi
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
// Đóng kết nối
$stmt->close();
$conn->close();
