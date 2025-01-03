<?php
session_start();
require_once "../src/db.php";
global $conn;

$user_id = $_SESSION['id'];
$assigned_to = $_POST['assigned_to'];
$message = $_POST['message'];
$message_id = $_POST['message_id']; // Nếu cần thiết

$role = $_SESSION['role'];

// if($role == '0'){
//     $sql = "INSERT INTO chat (message_id, user_id, assigned_to, message, status, created_at) VALUES ('$message_id', '$user_id', '$assigned_to','$message', 1, NOW())";

// }else{

//     $sql = "INSERT INTO chat (message_id, user_id, assigned_to, message, created_at) VALUES ('$message_id', '$user_id', '$assigned_to','$message', NOW())";
// }
// Câu lệnh SQL
$sql = "INSERT INTO chat (message_id, user_id, assigned_to, message, status, created_at) VALUES ('$message_id', '$user_id', '$assigned_to','$message', 1, NOW())";
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
