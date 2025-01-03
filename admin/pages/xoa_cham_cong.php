<?php
require_once "../src/db.php";
global $conn;
$id = $_GET['x'];
$sql = "delete from cham_cong where ID_cham_cong='$id'";
$ketqua = mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
if ($ketqua == true) {
     header("Location: ql_cham_cong.php");
}
