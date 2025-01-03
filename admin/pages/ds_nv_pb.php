<?php require 'header.php' ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$ca_lam_viec = $conn->query("SELECT * FROM ca_lam_viec");
$ma_pb = $_GET['ID'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin nhân viên phòng</h3>
                            <div class="card-tools row">
                                <select name="phong_ban" id="phongBan" class="mr-3">
                                    <option value="">Danh sách phòng ban</option>
                                    <?php while ($row = mysqli_fetch_assoc($bo_phan)) { ?>
                                        <option value="<?php echo $row['ID']; ?>"><?php echo $row['Ten']; ?></option>
                                    <?php } ?>
                                </select>
                                <button onclick="chuyenNhanVien()" class="btn btn-success">Chuyển nhân viên</button>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" onclick="toggleAllCheckboxes(this)"></th>
                                        <th>Mã NV</th>
                                        <th>Họ tên</th>
                                        <th>Phòng ban</th>
                                        <th>Bộ phận</th>
                                        <th>Chức danh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT nhan_vien.*, bo_phan.Ten, bo_phan.ten_bp
                                            FROM nhan_vien 
                                            JOIN bo_phan ON nhan_vien.ID_bophan = bo_phan.ID 
                                            WHERE bo_phan.ID = $ma_pb;";
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><input type="checkbox" class="nhanvien-checkbox" value="<?php echo $row['Ma_nv']; ?>"></td>
                                            <td><?php echo $row['Ma_nv']; ?></td>
                                            <td><?php echo $row['Hoten']; ?></td>
                                            <td><?php echo $row['Ten']; ?></td>
                                            <td><?php echo $row['ten_bp']; ?></td>
                                            <td><?php echo $row['chucdanh']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
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
    function toggleAllCheckboxes(source) {
        const checkboxes = document.querySelectorAll('.nhanvien-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = source.checked);
    }

    function chuyenNhanVien() {
        const selectedNhanVien = [];
        document.querySelectorAll('.nhanvien-checkbox:checked').forEach(checkbox => {
            selectedNhanVien.push(checkbox.value);
        });

        const phongBanId = document.getElementById('phongBan').value;

        if (selectedNhanVien.length === 0 || phongBanId === "") {
            alert("Vui lòng chọn nhân viên và phòng ban để chuyển.");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "chuyen_nhanvien.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText.trim() === "success") {
                    alert("Chuyển nhân viên thành công!");
                    location.reload();
                } else {
                    alert("Lỗi: " + xhr.responseText);
                }
            }
        };
        xhr.send("nhanvien=" + JSON.stringify(selectedNhanVien) + "&phongban=" + phongBanId);
    }
</script>

<?php require 'footer_ql.php' ?>