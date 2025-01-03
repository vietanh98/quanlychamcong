<?php
session_start();
require_once "../src/db.php";
global $conn;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_otp = $_POST['otp'];

    if ($input_otp == $_SESSION['otp']) {
        // Xóa OTP khỏi session

        // Chuyển hướng đến trang home.php
        $user = $_SESSION['Ma_nv'];
        $sql = " UPDATE nhan_vien SET login = 1 WHERE Ma_nv='$user'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            unset($_SESSION['otp']);
            header("Location: home.php");
        } else {
            echo 'Lỗi truy vấn';
        }
        exit();
    } else {
        echo '<script>alert("Mã OTP không đúng")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>

    <link rel="stylesheet" href="../Public/admin/dist/css/style_login.css">

    <style>
        .title_head {
            display: flex;
            justify-content: space-between;
        }

        .btn_close {
            text-decoration-line: none;
            font-weight: bold;
            background-color: #4eb8dd;
            border: 1px solid #ccc;
            color: #fff;
            padding: 6px 6px 6px 6px;
            margin-bottom: 10px;
        }

        .btn_close:hover {
            background-color: red;
        }
    </style>
</head>

<body>
    <div class="modal-content mt-5">
        <div class="modal-body mt-5">
            <form method="POST">
                <div class="form-group">
                    <div class="title_head">
                        <label style="padding-top:6px" for="otp">Mã OTP</label>
                        <a class="btn_close" style="" href="index.php">x</a>
                    </div>
                    <input type="text" class="form-control" name="otp" placeholder="Nhập mã OTP" required />
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top:10px">Xác nhận</button>
            </form>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Thêm Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js'></script>
    <script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/MorphSVGPlugin.min.js?r=182'></script>
    <script src="../Public/admin/dist/js/login_check.js"></script>

</body>


</html>