<?php require 'header.php'; ?>
<?php require_once "../src/db.php";
global $conn;

// Lấy tất cả người dùng từ bảng users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Phân quyền chức năng
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <!-- <a href="phan_quyen_cn.php"class="btn btn-success">Phân quyền chức năng</a> -->

                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Quản lý</th>
                                        <!-- <th>TK Admin</th> -->
                                        <th>QL nhân viên</th>
                                        <th>QL phòng ban</th>
                                        <th>Ca làm việc</th>
                                        <th>Chấm tăng ca</th>
                                        <th>Chấm bù OT</th>
                                        <th>Phân quyền</th>
                                        <th>Duyệt chấm công</th>
                                        <th>Sửa lịch làm</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) {
                                        $isManager = $row['role'] == '1' ? 'checked' : '';
                                        // $isTKAdmin = $row['TK_Admin'] == '1' ? 'checked' : '';
                                        $isQLNhanvien = $row['QL_Nhanvien'] == '1' ? 'checked' : '';
                                        $isQLPhongban = $row['QL_phongban'] == '1' ? 'checked' : '';
                                        $isCalamviec = $row['QL_calamviec'] == '1' ? 'checked' : '';
                                        $isChamtangca = $row['Chamtangca'] == '1' ? 'checked' : '';
                                        $isChambuOT = $row['Chambu'] == '1' ? 'checked' : '';
                                        $isDuyetchamcong = $row['Duyetchamcong'] == '1' ? 'checked' : '';
                                        $isPhanquyen = $row['Phanquyen'] == '1' ? 'checked' : '';
                                        $isSualichlam = $row['Sualichlam'] == '1' ? 'checked' : '';


                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                            <td><?php echo ($row['email']); ?></td>
                                            <td>
                                                <input type="checkbox" <?php echo $isManager; ?> onchange="updateRoleM('<?php echo $row['username']; ?>', '1', this)">
                                            </td>

                                            <!-- <td>
                                            <input type="checkbox" <?php echo $isTKAdmin; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'TK_Admin', this)">
                                        </td> -->
                                            <td>
                                                <input type="checkbox" <?php echo $isQLNhanvien; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'QL_Nhanvien', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isQLPhongban; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'QL_phongban', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isCalamviec; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'QL_calamviec', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isChamtangca; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'Chamtangca', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isChambuOT; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'Chambu', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isPhanquyen; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'Phanquyen', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isDuyetchamcong; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'Duyetchamcong', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isSualichlam; ?> onchange="updateRole('<?php echo $row['username']; ?>', 'Sualichlam', this)">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function updateRole(username, roleField, checkbox) {
        let roleValue = checkbox.checked ? '1' : '0';
        console.log(username, roleField, checkbox);

        // Gửi yêu cầu AJAX để cập nhật role
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_phanquyencn.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                if (!response.success) {
                    alert("Cập nhật thất bại: " + response.error);
                } else {
                    location.reload();
                }
            }
        };
        xhr.send(JSON.stringify({
            username: username,
            roleField: roleField,
            roleValue: roleValue
        }));
    }

    function updateRoleM(username, role, checkbox) {
        let newRole;
        if (checkbox.checked) {
            newRole = role;
        } else {
            newRole = '2'; // Default role if unchecked
        }

        // Gửi yêu cầu AJAX để cập nhật role
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_role.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                if (!response.success) {
                    alert("Cập nhật thất bại: " + response.error);
                } else {
                    location.reload();
                }
            }
        };
        xhr.send(JSON.stringify({
            username: username,
            role: newRole
        }));
    }
</script>

<style>
    /* Style cho table và checkbox có thể thêm vào đây */
</style>

<?php require 'footer_ql.php'; ?>