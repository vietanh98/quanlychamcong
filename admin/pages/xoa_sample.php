<?php
require_once "../src/db.php";
global $conn;
$id = $_GET['x'];
$sql = "delete from message_sample where id='$id'";
$ketqua = mysqli_query($conn, $sql) or die("Câu truy vấn sai!");
if ($ketqua == true) {
     header("Location:message_sample.php");
}
