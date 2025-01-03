<?php require 'header.php'; ?>
<?php require_once "../src/db.php";
global $conn;
$user_id = $_SESSION['id'];
$message_id = $_GET['message']; // Lấy ID tin nhắn từ URL

// Lấy thông tin tin nhắn giữa người dùng và người được chat từ bảng chat
$messages = $conn->query("
    SELECT c.*, 
           u.username AS sender_name, 
           u2.username AS receiver_name
    FROM chat c
    JOIN users u ON c.user_id = u.id 
    JOIN users u2 ON c.assigned_to = u2.id
    WHERE c.message_id = $message_id AND c.status = 1
    ORDER BY c.created_at ASC
");
$messages_admin = $conn->query("
    SELECT c.*, 
           u.username AS sender_name, 
           u2.username AS receiver_name
    FROM chat c
    JOIN users u ON c.user_id = u.id 
    JOIN users u2 ON c.assigned_to = u2.id
    WHERE c.message_id = $message_id
    ORDER BY c.created_at ASC
");

$messages_tp = $conn->query("
    SELECT c.*, 
           u.username AS sender_name, 
           u2.username AS receiver_name
    FROM chat c
    JOIN users u ON c.user_id = u.id 
    JOIN users u2 ON c.assigned_to = u2.id
    WHERE c.message_id = $message_id AND c.status = 1
    ORDER BY c.created_at ASC
");
if ($message_id != null) {
    // Truy vấn để lấy assigned_to từ bảng messages
    $stmt = $conn->prepare("SELECT assigned_to FROM messages WHERE id = ?");
    $stmt->bind_param("i", $message_id); // Gán tham số vào câu lệnh

    // Thực hiện câu lệnh
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $assigned_to = $row['assigned_to']; // Lấy giá trị assigned_to
        }
    } else {
        echo "Lỗi khi thực hiện truy vấn: " . $stmt->error;
    }

    // Đóng kết nối
    $stmt->close();
} else {
    echo "ID tin nhắn không hợp lệ.";
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Đoạn chat</h1>
                    <button onclick="window.location.href='message_history.php'" class="btn btn-back mt-2">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                        <li class="breadcrumb-item active">Đoạn chat</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <?php if ($_SESSION['role'] == '0') { ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body card-body-chat">
                                <!-- Chat messages detail here -->
                                <ul class="chat-messages">
                                    <?php if ($messages_admin->num_rows > 0): ?>
                                        <?php while ($row = $messages_admin->fetch_assoc()):
                                            $chat_with_id = $assigned_to;
                                        ?>
                                            <li class="chat-message <?php echo ($row['user_id'] == $user_id) ? 'sent' : 'received'; ?>">
                                                <div class="media">
                                                    <img src="../Public/avatardefault.png" alt="<?php echo htmlspecialchars($row['sender_name']); ?>" class="avatar mr-3 icon_chat" />
                                                    <div class="media-body d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong class="sender-name"><?php echo htmlspecialchars($row['sender_name']); ?></strong>
                                                            <p><?php echo $row['message']; ?></p>
                                                            <small><?php echo date('H:i, d-m-Y', strtotime($row['created_at'])); ?></small>
                                                        </div>
                                                        <!-- Icons for status -->
                                                        <!-- <div class="message-status" style="margin-left: 10px;">
                            <?php if ($row['status'] == 0) { ?>
                            <button class="btn btn-success btn-sm" onclick="approveMessage(<?php echo $row['id']; ?>)">
                                <i class="fas fa-check"></i>
                            </button>
                            <?php } ?>
                            <button class="btn btn-danger btn-sm ml-2" onclick="deleteMessage(<?php echo $row['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div> -->
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p>Chưa có tin nhắn nào.</p>
                                    <?php endif; ?>
                                </ul>


                                <!-- Input for new message -->
                                <div class="input-group mt-3">
                                    <input type="text" id="messageInput" class="form-control" placeholder="Nhập tin nhắn...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="sendMessageButton"> <i style="color: white;" class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    <?php } elseif ($_SESSION['role'] == '1') { ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body card-body-chat">
                                <!-- Chat messages detail here -->
                                <ul class="chat-messages">
                                    <?php if ($messages_tp->num_rows > 0): ?>

                                        <?php while ($row = $messages_tp->fetch_assoc()): $row['assigned_to'] ?>
                                            <li class="chat-message <?php echo ($row['user_id'] == $user_id) ? 'sent' : 'received'; ?>">
                                                <div class="media">
                                                    <img src="../Public/avatardefault.png" alt="<?php echo htmlspecialchars($row['sender_name']); ?>" class="avatar mr-3 icon_chat" />
                                                    <div class="media-body d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong class="sender-name"><?php echo htmlspecialchars($row['sender_name']); ?></strong>
                                                            <p><?php echo $row['message']; ?></p>
                                                            <small><?php echo date('H:i, d-m-Y', strtotime($row['created_at'])); ?></small>
                                                        </div>

                                                    </div>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p>Chưa có tin nhắn nào.</p>
                                    <?php endif; ?>
                                </ul>


                                <!-- Input for new message -->
                                <div class="input-group mt-3">
                                    <input type="text" id="messageInput" class="form-control" placeholder="Nhập tin nhắn...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="sendMessageButton"> <i style="color: white;" class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                                <form method="POST">
                                    <input type="hidden" name="id_message" value="<?php echo $_GET['message']; ?>">
                                    <div class="input-group mt-3">
                                        <button onclick="return confirm('Bạn có chắc chắn muốn kết thúc đoạn chat này?');" name="btn" type="submit" class="btn btn-danger w-100 fw-bold">Kết thúc đoạn chat</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    <?php } else { ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body card-body-chat">
                                <!-- Chat messages detail here -->
                                <ul class="chat-messages">
                                    <?php if ($messages->num_rows > 0): ?>
                                        <?php while ($row = $messages->fetch_assoc()):
                                            $avatar = ($row['user_id'] == $user_id) ?
                                                (isset($row['sender_avatar']) ?: '../Public/avatardefault.png') : (isset($row['receiver_avatar']) ?: '../Public/avatardefault.png');
                                        ?>
                                            <li class="chat-message <?php echo ($row['user_id'] == $user_id) ? 'sent' : 'received'; ?>">
                                                <div class="media">
                                                    <img src="<?php echo htmlspecialchars($avatar); ?>"
                                                        alt="<?php echo htmlspecialchars($row['sender_name']); ?>"
                                                        class="avatar mr-3"
                                                        onerror="this.src='../Public/avatardefault.png'" />
                                                    <div class="media-body">
                                                        <h5 class="mt-0"><?php echo htmlspecialchars($row['sender_name']); ?></h5>
                                                        <p><?php echo $row['message']; ?></p>
                                                        <small><?php echo date('H:i, d-m-Y', strtotime($row['created_at'])); ?></small>
                                                    </div>
                                                </div>
                                            </li>

                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p>Chưa có tin nhắn nào.</p>
                                    <?php endif; ?>
                                </ul>

                                <?php
                                $id_m = $_GET['message'];
                                $sql_m = "SELECT active FROM messages WHERE id = $id_m";
                                $result_m = mysqli_query($conn, $sql_m);
                                $row_m = $result_m->fetch_assoc();
                                $isActiveM = $row_m['active'];
                                if ($isActiveM == 1) {
                                ?>

                                    <!-- Input for new message -->
                                    <div class="input-group mt-3">
                                        <input type="text" id="messageInput" class="form-control" placeholder="Nhập tin nhắn...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" id="sendMessageButton"> <i style="color: white;" class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                    <form method="POST">
                                        <input type="hidden" name="id_message" value="<?php echo $_GET['message']; ?>">
                                        <div class="input-group mt-3">
                                            <button onclick="return confirm('Bạn có chắc chắn muốn kết thúc đoạn chat này?');" name="btn" type="submit" class="btn btn-danger w-100 fw-bold">Kết thúc đoạn chat</button>
                                        </div>
                                    </form>

                                <?php } ?>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    <?php } ?>

    <!-- Main content -->

    <!-- /.content -->
</div>

<?php
if (isset($_POST['btn'])) {
    $id_message = $_POST['id_message'];
    $sql1 = "UPDATE messages SET active = 0 WHERE id = $id_message";
    $result1 = mysqli_query($conn, $sql1);
}
?>

<style>
    .card-body-chat {
        padding: 20px;
        display: flex;
        flex-direction: column;
        height: 600px;
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .chat-messages {
        list-style-type: none;
        padding: 15px;
        max-height: 500px;
        overflow-y: auto;
        margin-bottom: 20px;
        flex-grow: 1;
        scrollbar-width: thin;
        scrollbar-color: #e0e0e0 transparent;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 3px;
    }

    .chat-message {
        padding: 8px;
        margin: 8px 0;
        display: flex;
        max-width: 80%;
    }

    .chat-message.sent {
        margin-left: auto;
        flex-direction: row-reverse;
    }

    .chat-message.received {
        margin-right: auto;
    }

    .media {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .chat-message.sent .media {
        flex-direction: row-reverse;
    }

    .chat-message.sent .avatar {
        margin-right: 0;
        margin-left: 12px;
    }

    .chat-message.sent .media-body {
        background: #007bff;
        color: white;
        margin-left: 0;
        margin-right: auto;
        border-radius: 15px;
        border-bottom-right-radius: 5px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .chat-message.sent .media-body small {
        text-align: right;
    }

    .avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 12px;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        object-fit: cover;
    }

    .media-body {
        padding: 12px 16px;
        border-radius: 15px;
        position: relative;
        max-width: 100%;
        word-wrap: break-word;
    }

    .chat-message.sent .media-body {
        background: #0084ff;
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 5px;
    }

    .chat-message.received .media-body {
        background: white;
        color: #333333;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        border-bottom-left-radius: 5px;
        border: 1px solid #e6e6e6;
    }

    .media-body small {
        font-size: 11px;
        opacity: 0.7;
        margin-top: 5px;
        display: block;
    }

    .input-group {
        background: white;
        padding: 15px;
        border-radius: 25px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .input-group input {
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        background: #f8f9fa;
    }

    .input-group input:focus {
        outline: none;
        box-shadow: none;
        background: #fff;
    }

    .input-group-append button {
        border-radius: 20px;
        padding: 8px 20px;
        margin-left: 10px;
        background: #0084ff;
        border: none;
        transition: all 0.3s ease;
    }

    .input-group-append button:hover {
        background: #0066cc;
        transform: translateY(-1px);
    }

    .input-group-append i {
        font-size: 16px;
    }

    .btn-back {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background: #e9ecef;
        color: #212529;
        transform: translateX(-2px);
    }

    .btn-back i {
        font-size: 14px;
    }

    .content-header h1 {
        margin-bottom: 0;
    }
</style>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sendMessageButton').addEventListener('click', function() {
            var messageInput = document.getElementById('messageInput');
            var message = messageInput.value;
            var assigned_to = <?php echo $assigned_to; ?>; // ID người nhận từ PHP
            var message_id = <?php echo $message_id; ?>;

            if (message.trim() === "") {
                alert("Vui lòng nhập tin nhắn!");
                return;
            }

            // Gửi yêu cầu AJAX để lưu tin nhắn
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_message.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log(xhr.responseText);
                    var response = JSON.parse(xhr.responseText);
                    location.reload();
                    if (response.success) {
                        // Nếu lưu thành công, thêm tin nhắn vào khung chat
                        var chatMessages = document.querySelector('.chat-messages');
                        var newMessage = document.createElement('li');
                        newMessage.className = 'chat-message sent';

                        newMessage.innerHTML = `
                        <div class="media">
                            <img src="../Public/avatardefault.png" alt="You" class="avatar mr-3" />
                            <div class="media-body">
                                <p>${message}</p>
                                <small>${new Date().toLocaleTimeString()} - ${new Date().toLocaleDateString()}</small>
                            </div>
                        </div>
                    `;

                        chatMessages.appendChild(newMessage); // Thêm tin nhắn mới vào danh sách
                        messageInput.value = ""; // Xóa input
                        chatMessages.scrollTop = chatMessages.scrollHeight; // Cuộn xuống dưới cùng
                    } else {
                        alert("Lưu tin nhắn thất bại: " + response.error);
                    }
                }
            };
            xhr.send("message=" + encodeURIComponent(message) + "&assigned_to=" + assigned_to + "&message_id=" + message_id);
        });
    });
</script>






<script>
    function approveMessage(chatId) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "update_chat.php?id=" + chatId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Tải lại trang khi cập nhật thành công
                    location.reload();
                } else {
                    alert("Lỗi: " + response.error);
                }
            }
        };
        xhr.send();
    }

    function deleteMessage(chatId) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "delete_chat.php?id=" + chatId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Tải lại trang khi cập nhật thành công
                    location.reload();
                } else {
                    alert("Lỗi: " + response.error);
                }
            }
        };
        xhr.send();
    }
</script>



<!-- /.content-wrapper -->
<?php require 'footer.php'; ?>