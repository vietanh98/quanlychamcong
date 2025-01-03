<?php
require_once "../src/db.php";
global $conn;
if ($_GET['x']) {
     $id = $_GET['x'];
} else {
     $id = $_GET['xnv'];
}

$sql = "delete from phan_ca_lam where id='$id'";
$ketqua = mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
if ($ketqua == true && $id = $_GET['x']) {
     header("Location:ql_ca_lam.php");
} else {
     header("Location:sua_lich_lam.php");
}
