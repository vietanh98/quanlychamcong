<?php
require_once "../src/db.php";
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$newRole = $data['role'];

// Cập nhật role trong bảng users
$stmt = $conn->prepare("UPDATE users SET role = ? WHERE username = ?");
$stmt->bind_param("ss", $newRole, $username);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Cập nhật không thành công']);
}
