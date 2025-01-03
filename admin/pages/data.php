<?php
require_once "../src/db.php";
global $conn;

header('Content-Type: application/json');
$data = array();
// $query = "SELECT Tinh_trang, COUNT(Tinh_trang) AS Tinhtrang FROM nhan_luong GROUP BY Tinh_trang";
$query = "SELECT Tong, SUM(Tong) AS Tong FROM nhan_luong GROUP BY Tong";
$stmt = $conn->prepare($query);
if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
foreach ($result as $row) {
    $data[] = $row;
}
echo json_encode($data);
