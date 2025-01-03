<?php
require_once "../src/db.php";
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$newApprove = $data['approve_message'];

// Cập nhật role trong bảng users
$stmt = $conn->prepare("UPDATE users SET approve_message = ? WHERE username = ?");
$stmt->bind_param("ss", $newApprove, $username);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Cập nhật không thành công']);
}
