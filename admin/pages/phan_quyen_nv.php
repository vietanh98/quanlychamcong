<?php require 'header.php'; ?>
<?php require_once "../src/db.php";
global $conn;

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Phân quyền nhân viên</h1>
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
                            <table id="example1" class="table table-bordered table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Mã NV</th>
                                        <th>Họ tên</th>
                                        <th>Giới tính</th>
                                        <th>Chức danh</th>
                                        <th>Phó phòng</th>
                                        <th>Sửa lịch làm việc</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php // Lấy tất cả người dùng từ bảng users dựa trên bộ phận của người dùng hiện tại
                                    $email = $_SESSION['username'];
                                    $sqlpb = "SELECT ID FROM bo_phan WHERE email = '$email'";
                                    $resultbp = $conn->query($sqlpb);
                                    $row1 = $resultbp->fetch_assoc();
                                    $Idbp = $row1['ID'];

                                    // Truy vấn để lấy dữ liệu nhân viên thuộc bộ phận của người dùng hiện tại
                                    $sql = "SELECT nv.Ma_nv, nv.Hoten, nv.Gioitinh, nv.chucdanh, u.Sualichlam, u.Phophong, u.email
        FROM nhan_vien nv 
        LEFT JOIN users u ON nv.username = u.email 
        LEFT JOIN bo_phan bp ON nv.ID_bophan = bp.ID 
        WHERE bp.ID = '$Idbp'";
                                    $result = mysqli_query($conn, $sql) or die("Câu lệnh truy vấn sai"); ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)) {
                                        $isSualichlam = $row['Sualichlam'] == '1' ? 'checked' : '';
                                        $isPhophong = $row['Phophong'] == '1' ? 'checked' : '';
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['Ma_nv']); ?></td>
                                            <td><?php echo htmlspecialchars($row['Hoten']); ?></td>
                                            <td><?php echo $row['Gioitinh']; ?></td>
                                            <td><?php echo htmlspecialchars($row['chucdanh']); ?></td>
                                            <td>
                                                <input type="checkbox" <?php echo $isPhophong; ?> onchange="updateRole('<?php echo $row['email']; ?>', 'Phophong', this)">
                                            </td>
                                            <td>
                                                <input type="checkbox" <?php echo $isSualichlam; ?> onchange="updateRole('<?php echo $row['email']; ?>', 'Sualichlamnv', this)">
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
                    //    location.reload();
                }
            }
        };
        xhr.send(JSON.stringify({
            username: username,
            roleField: roleField,
            roleValue: roleValue
        }));
    }
</script>

<?php require 'footer_ql.php'; ?>