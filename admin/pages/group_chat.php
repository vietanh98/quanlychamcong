<?php require 'header.php'; ?>
<?php require_once "../src/db.php";
global $conn;
$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

$userStatus = $conn->query("SELECT active_message FROM users WHERE id = $user_id")->fetch_assoc();
$isActiveMessage = $userStatus['active_message'] == 1;

// Lấy danh sách người dùng
$users = $conn->query("SELECT id, username, active_message FROM users");


// Lấy thông tin tin nhắn giữa người dùng và người được chat từ bảng group_chat
$messages = $conn->query("
    SELECT gc.*, 
           u.username AS sender_name 
    FROM group_chat gc
    JOIN users u ON gc.user_id = u.id 
    ORDER BY gc.created_at ASC
");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Group Chat </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                        <?php if ($role == '0') { ?>

                            <button class="btn btn-success" data-toggle="modal" data-target="#userPermissionModal">Cấp quyền chat group</button>
                        <?php } else if ($role == 'employee') { ?>
                            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                            <li class="breadcrumb-item active">Group Chat</li>

                        <?php } else { ?>

                            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                            <li class="breadcrumb-item active">Group Chat</li>


                        <?php  } ?>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body card-body-chat">
                            <!-- Chat messages detail here -->
                            <ul class="chat-messages">
                                <?php if ($messages->num_rows > 0): ?>
                                    <?php while ($row = $messages->fetch_assoc()): ?>
                                        <li class="chat-message <?php echo ($row['user_id'] == $user_id) ? 'sent' : 'received'; ?>">
                                            <div class="media">
                                                <!-- Hiển thị avatar và tên người gửi -->
                                                <img src="../Public/avatardefault.png" alt="<?php echo htmlspecialchars($row['sender_name']); ?>" class="avatar mr-3" />
                                                <div class="media-body">
                                                    <strong class="sender-name"><?php echo htmlspecialchars($row['sender_name']); ?></strong>
                                                    <!-- Hiển thị nội dung tin nhắn -->
                                                    <p><?php echo htmlspecialchars($row['message']); ?></p>
                                                    <!-- Hiển thị thời gian gửi -->
                                                    <small class="text-muted"><?php echo date('H:i, d-m-Y', strtotime($row['created_at'])); ?></small>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <li class="chat-message no-messages">Không có tin nhắn nào.</li>
                                <?php endif; ?>
                            </ul>

                            <!-- Input for new message -->
                            <?php if ($isActiveMessage) { ?>
                                <div class="input-group mt-3">
                                    <input type="text" id="messageInputGroup" class="form-control" placeholder="Nhập tin nhắn...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="sendMessageButtonGroup"><i style="color: white;" class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            <?php  } else { ?>
                                <div style="text-align:center" class="mt-3">
                                    <p><i class="fas fa-exclamation-triangle" style="color: orange;"></i> Chỉ <span class="text-primary">admin</span> và <span class="text-primary">thành viên</span> được chỉ định mới được quyền gửi tin nhắn vào cộng đồng!</p>
                                </div>
                        </div>
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
</div>

<!-- Modal -->
<div class="modal fade" id="userPermissionModal" tabindex="-1" role="dialog" aria-labelledby="userPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userPermissionModalLabel">Cấp Quyền Chat Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="userList">
                    <!-- Danh sách người dùng sẽ được chèn vào đây -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="savePermissions">Lưu Thay Đổi</button>
            </div>
        </div>
    </div>
</div>


<style>
    .card-body-chat {
        padding: 20px;
        display: flex;
        flex-direction: column;
        height: 500px;
        /* Đặt chiều cao cho card-body */
    }

    .chat-messages {
        list-style-type: none;
        padding: 0;
        max-height: 400px;
        /* Chiều cao cố định cho khung tin nhắn */
        overflow-y: auto;
        /* Cuộn khi đầy */
        margin-bottom: 20px;
        /* Khoảng cách dưới cùng */
        flex-grow: 1;
        /* Để chiếm không gian còn lại */
    }

    .chat-message {
        padding: 10px;
        margin: 5px 0;
        /* Khoảng cách giữa các tin nhắn */
        display: flex;
    }

    .chat-message.sent {
        justify-content: flex-end;
        /* Tin nhắn gửi nằm bên phải */
    }

    .chat-message.received {
        justify-content: flex-start;
        /* Tin nhắn nhận nằm bên trái */
    }

    .media-body {
        max-width: 100%;
        /* Giới hạn chiều rộng hộp tin nhắn */
        padding: 10px;
        border-radius: 5px;
        position: relative;
        color: white;
        /* Màu chữ cho tin nhắn */
    }

    .chat-message.sent .media-body {
        background-color: #007bff;
        /* Màu nền cho tin nhắn gửi */
        margin-left: auto;
        /* Căn phải */
    }

    .chat-message.received .media-body {
        background-color: #f1f1f1;
        /* Màu nền cho tin nhắn nhận */
        color: black;
        /* Màu chữ cho tin nhắn nhận */
        margin-right: auto;
        /* Căn trái */
    }

    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .input-group {
        margin-top: auto;
        /* Đẩy phần input xuống dưới cùng */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var userList = document.getElementById('userList');

        <?php while ($user = $users->fetch_assoc()): ?>
            var listItem = document.createElement('li');
            listItem.className = 'list-group-item';
            listItem.innerHTML = `
                <input type="checkbox" id="user_<?php echo $user['id']; ?>" value="<?php echo $user['id']; ?>" 
                <?php echo $user['active_message'] == 1 ? 'checked' : ''; ?>> 
                <?php echo htmlspecialchars($user['username']); ?>
            `;
            userList.appendChild(listItem);
        <?php endwhile; ?>
    });

    document.getElementById('savePermissions').addEventListener('click', function() {
        var userPermissions = {};
        var checkboxes = document.querySelectorAll('#userList input[type="checkbox"]');

        checkboxes.forEach(function(checkbox) {
            userPermissions[checkbox.value] = checkbox.checked ? 1 : 0;
        });

        // Gửi yêu cầu AJAX để cập nhật quyền
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_permissions.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Cập nhật thành công!');
                    $('#userPermissionModal').modal('hide'); // Đóng modal
                } else {
                    alert("Cập nhật thất bại: " + response.error);
                }
            }
        };
        xhr.send(JSON.stringify(userPermissions));
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sendMessageButtonGroup').addEventListener('click', function() {
            var messageInput = document.getElementById('messageInputGroup');
            var message = messageInput.value;

            if (message.trim() === "") {
                alert("Vui lòng nhập tin nhắn!");
                return;
            }

            // Gửi yêu cầu AJAX để lưu tin nhắn
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_message_group.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        // Nếu lưu thành công, thêm tin nhắn vào khung chat
                        var chatMessages = document.querySelector('.chat-messages');
                        var newMessage = document.createElement('li');
                        newMessage.className = 'chat-message sent';

                        newMessage.innerHTML = `
                            <div class="media">
                                <img src="../Public/avatardefault.png" alt="You" class="avatar mr-3" />
                                <div class="media-body">
                                    <strong class="sender-name">You</strong>
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
            xhr.send("message=" + encodeURIComponent(message));
        });

        function loadMessages() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "load_messages.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    var response = JSON.parse(xhr.responseText);
                    var chatMessages = document.querySelector('.chat-messages');
                    chatMessages.innerHTML = ""; // Xóa tin nhắn cũ

                    response.forEach(function(row) {
                        var newMessage = document.createElement('li');
                        newMessage.className = 'chat-message ' + (row.user_id == <?php echo $user_id; ?> ? 'sent' : 'received');
                        newMessage.innerHTML = `
                            <div class="media">
                                <img src="../Public/avatardefault.png" alt="${row.sender_name}" class="avatar mr-3" />
                                <div class="media-body">
                                    <strong class="sender-name">${row.sender_name}</strong>
                                    <p>${row.message}</p>
                                    <small>${new Date(row.created_at).toLocaleTimeString()} - ${new Date(row.created_at).toLocaleDateString()}</small>
                                </div>
                            </div>
                        `;
                        chatMessages.appendChild(newMessage); // Thêm tin nhắn mới vào danh sách
                    });

                    chatMessages.scrollTop = chatMessages.scrollHeight; // Cuộn xuống dưới cùng
                }
            };
            xhr.send();
        }

        // Tải tin nhắn lần đầu tiên
        loadMessages();
        // Tải tin nhắn mới mỗi 2 giây
        // setInterval(loadMessages, 2000);
    });
</script>

<!-- /.content-wrapper -->
<?php require 'footer.php'; ?>