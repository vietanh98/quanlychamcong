<?php session_start();
require_once "../src/db.php";
global $conn;
$user_id = $_SESSION['id'];
$resultUserNow = $conn->query("SELECT * FROM users WHERE id = $user_id");

$rowUserNow = $resultUserNow->fetch_assoc();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý chấm công</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="../Public/admin/dist/img/LogoCF.png" /> -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../Public/admin/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../Public/admin/plugins/fontawesome-free-6.2.0-web/css/all.min.css ">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="../Public/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../Public/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../Public/admin/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../Public/admin/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../Public/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../Public/admin/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../Public/admin/plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../Public/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../Public/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../Public/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../Public/admin/dist/css/adminlte.min.css">
    <link href='https://unpkg.com/@fullcalendar/common@latest/main.css' rel='stylesheet' />
    <link href='https://unpkg.com/@fullcalendar/daygrid@latest/main.css' rel='stylesheet' />
    <link href='https://unpkg.com/@fullcalendar/timegrid@latest/main.css' rel='stylesheet' />
    <script>
        // // Hàm khởi tạo đồng hồ
        // function startTime() {
        //     // Lấy Object ngày hiện tại
        //     var today = new Date();

        //     // Giờ, phút, giây hiện tại
        //     var h = today.getHours();
        //     var m = today.getMinutes();
        //     var s = today.getSeconds();

        //     // Chuyển đổi sang dạng 01, 02, 03
        //     m = checkTime(m);
        //     s = checkTime(s);

        //     // Ghi ra trình duyệt
        //     document.getElementById('timer').innerHTML = h + ":" + m + ":" + s;

        //     // Dùng hàm setTimeout để thiết lập gọi lại 0.5 giây / lần
        //     var t = setTimeout(function() {
        //         startTime();
        //     }, 500);
        // }

        // // Hàm này có tác dụng chuyển những số bé hơn 10 thành dạng 01, 02, 03, ...
        // function checkTime(i) {
        //     if (i < 10) {
        //         i = "0" + i;
        //     }
        //     return i;
        // }
        // 
    </script>

    <style>
        #logout-link:hover {
            cursor: pointer;
        }
    </style>


</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">
        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../Public/admin/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div> -->
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="home.php" class="nav-link">Home</a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                        role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="" class="brand-link" style="background-color: black;">
                <!-- <img src="../Public/admin/dist/img/LogoCF.png" alt="" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
                <span class="brand-text font-weight-light">TRANG CHẤM CÔNG</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar" style="background-color: black;">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../Public/admin/dist/img/admin.png" class="img-circle elevation-2" alt="">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php if (isset($_SESSION['username'])) {
                                                        echo strtoupper($_SESSION['username']);
                                                    } ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                        <li class="nav-item has-treeview">
                            <a href="home.php" class="nav-link">
                                <i class="nav-icon fa-solid fa-house"></i>
                                <p>Trang chủ</p>
                            </a>
                        </li>
                        <?php if ($_SESSION['role'] == 'employee') { ?>
                            <li class="nav-item has-treeview">
                                <a href="data_history.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-calendar"></i>
                                    <p>Lịch sử chấm công</p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="message_history.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-message"></i>
                                    <p>Tin nhắn</p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="group_chat.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-comments"></i>
                                    <p>Group chat</p>
                                </a>

                            </li>
                            <li class="nav-item has-treeview">
                                <a href="group_chat_nb.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-inbox"></i>
                                    <p>Group chat nội bộ team</p>
                                </a>
                            </li>
                            <?php if ($rowUserNow['Sualichlam']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="sua_lich_lam.php" class="nav-link">
                                        <i class="nav-icon fas fa-calendar-week"></i>
                                        <p>Sửa lịch làm việc</p>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } else if ($_SESSION['role'] == 0) { ?>

                            <?php if ($rowUserNow['TK_Admin']) { ?>
                                <li class="nav-item has-treeview">
                                    <a href="add_admin.php" class="nav-link">
                                        <i class="nav-icon fas fa-lock"></i>
                                        <p>Tài khoản admin</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($rowUserNow['QL_Nhanvien']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="ql_nhan_vien.php" class="nav-link">
                                        <i class="nav-icon far fa-user"></i>
                                        <p>Nhân viên</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($rowUserNow['QL_phongban']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="ql_bo_phan.php" class="nav-link">
                                        <i class="nav-icon fa-solid fa-users"></i>
                                        <p>Phòng ban</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($rowUserNow['QL_calamviec']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="ql_ca_lam.php" class="nav-link">
                                        <i class="nav-icon fa-regular fa-clock"></i>
                                        <p>Ca làm việc</p>
                                    </a>
                                </li>
                            <?php } ?>

                            <li class="nav-item has-treeview">
                                <a href="thong_ke.php" class="nav-link">
                                    <i class="nav-icon far fa-calendar"></i>
                                    <p>Số ngày làm</p>
                                </a>
                            </li>

                            <li class="nav-item has-treeview">
                                <a href="message_history.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-message"></i>
                                    <p>Tin nhắn</p>
                                </a>
                            </li>

                            <li class="nav-item has-treeview">
                                <a href="message_sample.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-inbox"></i>
                                    <p>Mẫu nhắn tin</p>
                                </a>
                            </li>

                            <li class="nav-item has-treeview">
                                <a href="group_chat.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-comments"></i>
                                    <p>Group chat</p>
                                </a>
                            </li>
                            <?php if ($rowUserNow['Phanquyen']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="phan_quyen.php" class="nav-link">
                                        <i class="nav-icon fa-solid fa-cog"></i>
                                        <p>Phân quyền</p>
                                    </a>
                                </li>
                            <?php } ?>



                        <?php } else if ($_SESSION['role'] == 1) { ?>
                            <?php if ($rowUserNow['QL_Nhanvien']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="ql_nhan_vien.php" class="nav-link">
                                        <i class="nav-icon far fa-user"></i>
                                        <p>Nhân viên</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($rowUserNow['QL_phongban']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="ql_bo_phan.php" class="nav-link">
                                        <i class="nav-icon fa-solid fa-users"></i>
                                        <p>Phòng ban</p>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($rowUserNow['QL_calamviec']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="ql_ca_lam.php" class="nav-link">
                                        <i class="nav-icon fa-regular fa-clock"></i>
                                        <p>Ca làm việc</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($rowUserNow['Chambu']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="ql_ot.php" class="nav-link">
                                        <i class="nav-icon fa-solid fa-pencil"></i>
                                        <p>Chấm bù OT</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($rowUserNow['Chamtangca']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="ql_tang_ca.php" class="nav-link">
                                        <i class="nav-icon fa-solid fa-arrow-up"></i>
                                        <p>Tăng ca</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($rowUserNow['Duyetchamcong']) { ?>

                                <li class="nav-item has-treeview">
                                    <a href="duyet_cham_cong.php" class="nav-link">
                                        <i class="nav-icon far fa-calendar-check"></i>
                                        <p>Duyệt chấm công</p>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($rowUserNow['Phanquyen']) { ?>
                                <li class="nav-item has-treeview">
                                    <a href="phan_quyencn.php" class="nav-link">
                                        <i class="nav-icon fas fa-calendar-week"></i>
                                        <p>Phân quyền</p>
                                    </a>
                                </li>
                            <?php } ?>

                            <li class="nav-item has-treeview">
                                <a href="thong_ke.php" class="nav-link">
                                    <i class="nav-icon far fa-calendar"></i>
                                    <p>Số ngày làm</p>
                                </a>
                            </li>



                            <li class="nav-item has-treeview">
                                <a href="message_history.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-message"></i>
                                    <p>Tin nhắn</p>
                                </a>
                            </li>

                            <li class="nav-item has-treeview">
                                <a href="group_chat.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-comments"></i>
                                    <p>Group chat</p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="group_chat_nb.php" class="nav-link">
                                    <i class="nav-icon fa-solid fa-inbox"></i>
                                    <p>Group chat nội bộ team</p>
                                </a>
                            </li>

                        <?php } ?>
                        <li class="nav-item has-treeview">
                            <a class="nav-link" id="logout-link">
                                <i class="nav-icon fa-sharp fa-solid fa-right-from-bracket"></i>
                                <p>Đăng xuất</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const navItems = document.querySelectorAll('.nav-item');
                const activeLink = localStorage.getItem('activeLink'); // Lấy giá trị từ localStorage

                // Nếu có giá trị activeLink, thêm class active cho thẻ tương ứng
                if (activeLink) {
                    navItems.forEach(item => {
                        const link = item.querySelector('a');
                        if (link.getAttribute('href') === activeLink) {
                            link.classList.add('active');
                        }
                    });
                }

                navItems.forEach(item => {
                    item.addEventListener('click', function() {
                        // Xóa class 'active' từ tất cả các thẻ nav-link
                        navItems.forEach(i => i.querySelector('a').classList.remove('active'));

                        // Thêm class 'active' vào thẻ a trong thẻ li đã click
                        this.querySelector('a').classList.add('active');

                        // Lưu trữ href của thẻ a đã click vào localStorage
                        localStorage.setItem('activeLink', this.querySelector('a').getAttribute(
                            'href'));
                    });
                });

                // Xóa activeLink và class active khi người dùng đăng xuất
                document.getElementById('logout-link').addEventListener('click', function(e) {
                    e.preventDefault(); // Ngăn chặn hành vi mặc định của liên kết

                    // Xóa trạng thái activeLink và class active
                    localStorage.removeItem('activeLink'); // Xóa trạng thái activeLink

                    // Xóa class active từ tất cả các thẻ a
                    navItems.forEach(item => {
                        item.querySelector('a').classList.remove('active');
                    });

                    // Chuyển hướng đến trang đăng xuất
                    window.location.href = '../pages/dang_xuat.php';
                });
            });
        </script>