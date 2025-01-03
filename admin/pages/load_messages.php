<?php
require_once "../src/db.php";
global $conn;
session_start();
$user_id = $_SESSION['id'];

// Lấy thông tin tin nhắn từ bảng group_chat
$messages = $conn->query("
    SELECT gc.*, 
           u.username AS sender_name
    FROM group_chat gc
    JOIN users u ON gc.user_id = u.id
    ORDER BY gc.created_at ASC
");

$response = [];
if ($messages->num_rows > 0) {
    while ($row = $messages->fetch_assoc()) {
        $response[] = $row;
    }
}

echo json_encode($response);
