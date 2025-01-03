<?php
$servername = "127.0.0.1"; //Khai báo server
$username = "root"; // Khai báo username
$password = "Anh911998"; // Khai báo password
$dbname = "quanlychamcong"; // Khai báo database
//Kết nối cơ sở dữ liệu 
$conn = mysqli_connect($servername, $username, $password, $dbname);
//Nếu kết nối bị lỗi thì xuất báo lỗi và thoát.
if (!$conn) {
    die("Không kết nối :" . mysqli_connect_error());
    exit();
}
