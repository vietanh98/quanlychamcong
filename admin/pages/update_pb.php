<?php
require_once "../src/db.php";
global $conn;

// Lấy dữ liệu từ yêu cầu AJAX
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$bo_phan_id = $data['bo_phan_id'];

// Tìm phòng ban hiện tại của người dùng
$sql_find_current = "SELECT ID FROM bo_phan WHERE email = ?";
$stmt_find_current = $conn->prepare($sql_find_current);
$stmt_find_current->bind_param("s", $username);
$stmt_find_current->execute();
$result_find_current = $stmt_find_current->get_result();
$current_bo_phan = $result_find_current->fetch_assoc();

// Nếu có phòng ban hiện tại, xóa email khỏi phòng ban đó
if ($current_bo_phan) {
    $sql_clear_email = "UPDATE bo_phan SET email = NULL WHERE ID = ?";
    $stmt_clear_email = $conn->prepare($sql_clear_email);
    $stmt_clear_email->bind_param("i", $current_bo_phan['ID']);
    $stmt_clear_email->execute();
}

// Kiểm tra xem phòng ban mới đã có quản lý chưa
$sql_check = "SELECT email FROM bo_phan WHERE ID = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $bo_phan_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();

if ($row_check && !empty($row_check['email'])) {
    // Nếu phòng ban mới đã có quản lý, trả về lỗi
    $response = [
        'success' => false,
        'error' => 'Phòng ban này đã có quản lý'
    ];
} else {
    // Nếu phòng ban mới chưa có quản lý, cập nhật email vào phòng ban mới
    $sql_update = "UPDATE bo_phan SET email = ? WHERE ID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $username, $bo_phan_id);
    $response = ['success' => $stmt_update->execute()];
}

echo json_encode($response);
