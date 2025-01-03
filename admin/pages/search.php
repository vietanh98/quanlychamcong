<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer</title>
</head>

<body>
    <h3>Tìm theo tên</h3>
    <form action="" method="post">
        <div>
            Last Name: <input name="last_name" />
            <input type="submit" value="Search" />
        </div>
    </form>
    <h3>Result:</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            /*Kết nối máy chủ MySQL. Máy chủ có cài đặt mặc định (user là 'root' và không có mật khẩu)*/
            require_once "../src/db.php";
            global $conn;

            // Kểm tra kết nối
            if ($conn === false) {
                die("ERROR: Không thể kết nối. " . mysqli_connect_error());
            }

            $lname = '%';
            if (isset($_POST['last_name'])) {
                $lname = '%' . $_POST['last_name'] . '%';
            }
            // Thực hiện câu lệnh SELECT
            $sql = "SELECT * FROM customer WHERE last_name LIKE '$lname'";
            if ($result = mysqli_query($conn, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
            ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                        </tr>
                    <?php
                    }
                    // Giải phóng bộ nhớ của biến
                    mysqli_free_result($result);
                } else {
                    ?>
                    <tr>
                        <td colspan="4">No Records.</td>
                    </tr>
            <?php
                }
            } else {
                echo "ERROR: Không thể thực thi câu lệnh $sql. " . mysqli_error($link);
            }
            // Đóng kết nối
            mysqli_close($link);
            ?>
        </tbody>
    </table>
</body>

</html>