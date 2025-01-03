<?php
require_once "../src/db.php";
global $conn;
global $conn;
$id = $_GET['x'];
$sql = "delete from ung_luong where ID='$id'";
$ketqua = mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
if ($ketqua == true) {
     header("Location:ql_ung_luong.php");
}
