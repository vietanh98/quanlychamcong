<?php
require_once "../src/db.php";
global $conn;
$id = $_GET['x'];
$sql = "delete from ca_lam_viec where ID='$id'";
$ketqua = mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
if ($ketqua == true) {
     header("Location:ql_ca_lam.php");
}
