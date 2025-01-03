<?php
session_start();
require_once "../src/db.php";
global $conn;

$user_id = $_SESSION['id'];
$message = $_POST['message'];


$role = $_SESSION['role'];

$sql = "INSERT INTO group_chat (user_id, message, created_at) VALUES ('$user_id','$message', NOW())";

// Chuẩn bị câu lệnh
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'error' => 'Database prepare error: ' . mysqli_error($conn)]);
    exit();
}


// Thực hiện câu lệnh
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_stmt_error($stmt)]);
}

// Đóng câu lệnh và kết nối
mysqli_stmt_close($stmt);
mysqli_close($conn);
