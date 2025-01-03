<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec"); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Mẫu tin nhắn gợi ý</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Thêm tin nhắn</h3>

                </div>
                <form method="post">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Nội dung tin nhắn gợi ý</label>
                                    <textarea type="text" name="message" class="form-control" placeholder="Nội dung" required value=""></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="mb-2">
                            <button type="submit" name="btn" class="btn btn-success w-100">Thêm</button>

                        </div>
                    </div>
                    <?php
                    if (isset($_POST['btn'])) {
                        $message = $_POST['message'];

                        $sql1 = "INSERT INTO message_sample VALUES(id,'$message')";
                        $result1 = $conn->query($sql1);
                        if ($result1 == true) {
                            echo "<script> alert('Thêm thành công');</script>";
                            // echo "<script> location.reload();</script>";
                        } else {
                            echo "<script> alert('Có lỗi xảy ra: " . mysqli_error($conn) . "');</script>";
                        }
                    }

                    ?>
                </form>

            </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Nội dung</th>
                                        <th>Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Câu SQL lấy danh sách
                                    $sql = " SELECT *
                                             FROM message_sample 
                                            
                                            ;";
                                    $stt = 0;
                                    // Thực thi câu truy vấn và gán vào $result
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['content']; ?></td>
                                            <td>
                                                <button class="btn btn-danger" onclick="if (confirm('Bạn có chắc chắn muốn xóa?')) window.location.href='xoa_sample.php?x=<?php echo $row['id'] ?>'"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

</div>

<script>
    function updateStatus(maNV, switchElement) {
        const status = switchElement.checked ? 1 : 0; // Trạng thái 1 hoặc 0
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("Trạng thái đã được cập nhật.");
            }
        };
        xhr.send("Ma_nv=" + maNV + "&status=" + status);
    }
</script>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(14px);
    }
</style>
<!-- /.content-wrapper -->

<?php require 'footer_ql.php' ?>