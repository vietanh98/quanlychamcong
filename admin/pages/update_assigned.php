<?php
session_start();
require_once "../src/db.php";
global $conn;


// Nhận dữ liệu từ yêu cầu POST
$message_id = $_POST['message_id'];
$assigned_to = $_POST['assigned_to'];
$user_id = $_POST['sender_id'];
$message = $_POST['message_content'];


// Cập nhật trường assigned_to trong bảng chat
$stmt = $conn->prepare("UPDATE messages SET assigned_to = $assigned_to , status = 1 WHERE id = $message_id");
$stmt1 = $conn->prepare("INSERT INTO chat  VALUES (id,'$message_id', '$user_id', '$assigned_to', '$message',2, 1, NOW())");


// Thực hiện câu lệnh
if ($stmt->execute() && $stmt1->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

// Đóng kết nối
$stmt->close();
$conn->close();
