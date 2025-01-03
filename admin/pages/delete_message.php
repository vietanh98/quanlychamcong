<?php
session_start();
require_once "../src/db.php";
global $conn;


// Nhận dữ liệu từ yêu cầu POST
$message_id = $_POST['message_id'];
// $assigned_to = $_POST['assigned_to'];

// Cập nhật trường assigned_to trong bảng chat
$stmt = $conn->prepare("DELETE FROM messages WHERE id = $message_id");

// Thực hiện câu lệnh
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

// Đóng kết nối
$stmt->close();
$conn->close();
