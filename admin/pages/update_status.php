<?php
require_once "../src/db.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_nv = $_POST['Ma_nv'];
    $status = $_POST['status'];

    $sql = "UPDATE nhan_vien SET status = ? WHERE Ma_nv = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $status, $ma_nv);
    $stmt->execute();
    $stmt->close();
}
