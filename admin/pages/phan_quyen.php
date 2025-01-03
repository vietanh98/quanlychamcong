<?php require 'header.php'; ?>
<?php require_once "../src/db.php";
global $conn;

// Lấy tất cả người dùng từ bảng users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai");

$sql_bo_phan = "SELECT ID, Ten FROM bo_phan";
$result_bo_phan = mysqli_query($conn, $sql_bo_phan) or die("Câu lệnh truy vấn sai");

// Lưu các phòng ban vào một mảng để dùng trong dropdown
$bo_phan_list = [];
while ($row = mysqli_fetch_assoc($result_bo_phan)) {
    $bo_phan_list[] = $row;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Phân quyền tài khoản
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <a href="phan_quyencn.php" class="btn btn-success">Phân quyền chức năng</a>

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
                                        <th>Quản lý phòng ban</th>
                                        <th>Admin</th>
                                        <th>Quản lý</th>
                                        <th>Nhân viên</th>
                                        <th>Duyệt tin nhắn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) {
                                        $isAdmin = $row['role'] == '0' ? 'checked' : '';
                                        $isManager = $row['role'] == '1' ? 'checked' : '';
                                        $isEmployee = $row['role'] == '2' ? 'checked' : '';
                                        $approved = $row['approve_message'] == '1' ? 'checked' : '';

                                        // Tìm kiếm phòng ban hiện tại của người dùng bằng cách kiểm tra email
                                        $email = $row['username'];
                                        $sql_current_bo_phan = "SELECT ID FROM bo_phan WHERE email = '$email'";
                                        $result_current_bo_phan = mysqli_query($conn, $sql_current_bo_phan);
                                        $current_bo_phan = $result_current_bo_phan->fetch_assoc();
                                        $current_bo_phan_id = $current_bo_phan['ID'] ?? '';
                                        // var_dump($current_bo_phan_id);
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <?php if ($row['role'] == '1') { ?>
                                                <td>
                                                    <select onchange="updateBoPhan('<?php echo $row['username']; ?>', this.value)">
                                                        <option value="">Chọn phòng ban</option>
                                                        <?php foreach ($bo_phan_list as $bo_phan) { ?>
                                                            <option value="<?php echo $bo_phan['ID']; ?>" <?php echo $bo_phan['ID'] == $current_bo_phan_id ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($bo_phan['Ten']); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            <?php } else { ?>
                                                <td></td>
                                            <?php } ?>
                                            <td>
                                                <input type="checkbox" <?php echo $isAdmin; ?> onchange="updateRole('<?php echo $row['username']; ?>', '0', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isManager; ?> onchange="updateRole('<?php echo $row['username']; ?>', '1', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isEmployee; ?> onchange="updateRole('<?php echo $row['username']; ?>', '2', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $approved; ?> onchange="updateApproveMessage('<?php echo $row['username']; ?>', '1', this)">
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
    function updateRole(username, role, checkbox) {
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
                    //    location.reload();
                }
            }
        };
        xhr.send(JSON.stringify({
            username: username,
            role: newRole
        }));
    }

    function updateApproveMessage(username, role, checkbox) {
        let newApprove;
        if (checkbox.checked) {
            newApprove = role;
        } else {
            newApprove = '0'; // Default role if unchecked
        }

        // Gửi yêu cầu AJAX để cập nhật role
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_approve_message.php", true);
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
            approve_message: newApprove
        }));
    }

    function updateBoPhan(username, boPhanId) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_pb.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                if (!response.success) {
                    alert(response.error);
                    location.reload();
                } else {
                    alert("Cập nhật thành công");
                    location.reload();
                }
            }
        };
        xhr.send(JSON.stringify({
            username: username,
            bo_phan_id: boPhanId
        }));
    }
</script>

<style>
    /* Style cho table và checkbox có thể thêm vào đây */
</style>

<?php require 'footer_ql.php'; ?>