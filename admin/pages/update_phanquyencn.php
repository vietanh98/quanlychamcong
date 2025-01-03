<?php
require_once "../src/db.php";
global $conn;

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$roleField = $data['roleField']; // Trường quyền cụ thể như TK_Admin, QL_Nhanvien...
$roleValue = $data['roleValue']; // Giá trị quyền (1 hoặc 0)

// Xác thực input để tránh SQL Injection
$allowedFields = ['TK_Admin', 'QL_Nhanvien', 'QL_phongban', 'QL_calamviec', 'Chamtangca', 'Chambu', 'Phanquyen', 'Duyetchamcong', 'Sualichlam', 'Sualichlamnv', 'Phophong'];
if (!in_array($roleField, $allowedFields)) {
    echo json_encode(['success' => false, 'error' => 'Trường quyền không hợp lệ']);
    exit;
}

if ($allowedFields = 'Sualichlamnv') {
    $stmt1 = $conn->prepare("UPDATE users SET Sualichlam = ? WHERE email = ?");
    $stmt1->bind_param("ss", $roleValue, $username);
    $stmt1->execute();
}

if ($allowedFields = 'Phophong') {
    $stmt1 = $conn->prepare("UPDATE users SET Phophong = ? WHERE email = ?");
    $stmt1->bind_param("ss", $roleValue, $username);
    $stmt1->execute();
}

// Cập nhật quyền cụ thể trong bảng users
$stmt = $conn->prepare("UPDATE users SET $roleField = ? WHERE username = ?");
$stmt->bind_param("ss", $roleValue, $username);
$stmt->execute();

if ($stmt->affected_rows > 0 || $stmt1->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Cập nhật không thành công hoặc không có thay đổi']);
}

$stmt->close();
$conn->close();
