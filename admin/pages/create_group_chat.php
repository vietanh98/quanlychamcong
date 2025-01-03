<?php
session_start();
require_once "../src/db.php";
global $conn;


// Nhận dữ liệu từ yêu cầu POST
$user_id = $_POST['user_id'];
$assigned_to = $_POST['assigned_to'];
$message = "Tôi là admin";
// Cập nhật trường assigned_to trong bảng chat
$stmt = $conn->prepare("INSERT INTO `messages` (`user_id`, `assigned_to`, `message`, `status`, `active`) VALUES (?, ?, ?, '1', '1')");
$stmt->bind_param("iis", $user_id, $assigned_to, $message);

// Thực hiện câu lệnh
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

// Đóng kết nối
$stmt->close();
$conn->close();
