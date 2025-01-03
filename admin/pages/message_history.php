<?php require 'header.php'; ?>
<?php require_once "../src/db.php";
global $conn;
$bo_phan = $conn->query("SELECT * FROM bo_phan");
$user_id = $_SESSION['id'];

// Sample query to get chat messages, adjust as necessary
// $messages = $conn->query("SELECT m.*, u.username FROM messages m JOIN users u ON m.user_id = u.id  ORDER BY m.created_at DESC"); 

$messages = $conn->query("
    SELECT m.*, 
           u.username AS sender_name, 
           u2.username AS assigned_name
    FROM messages m 
    JOIN users u ON m.user_id = u.id 
    LEFT JOIN users u2 ON m.assigned_to = u2.id
    WHERE m.user_id = $user_id
    ORDER BY m.created_at DESC
");
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$messages_admin = $conn->query("
    SELECT m.*, 
           u.username AS sender_name, 
           u.email AS sender_email, 
           u2.username AS assigned_name,
           (SELECT c.message 
            FROM chat c 
            WHERE c.message_id = m.id 
            ORDER BY c.created_at DESC 
            LIMIT 1) as last_message
    FROM messages m 
    JOIN users u ON m.user_id = u.id 
    LEFT JOIN users u2 ON m.assigned_to = u2.id
    WHERE (u.username LIKE '%$search%' OR u.email LIKE '%$search%')
    ORDER BY m.created_at DESC
");

$messages_tp = $conn->query("
    SELECT m.*, 
           u.username AS sender_name, 
           u2.username AS assigned_name
    FROM messages m 
    JOIN users u ON m.user_id = u.id 
    LEFT JOIN users u2 ON m.assigned_to = u2.id
    WHERE m.assigned_to = $user_id
    ORDER BY m.created_at DESC
");
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Lịch sử chat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                        <li class="breadcrumb-item active">Lịch sử chat</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- <?php var_dump($rowUserNow['username']) ?> -->


    <?php if ($_SESSION['role'] == '0' || $rowUserNow['approve_message'] == '1') { ?>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <button class="btn btn-success" data-toggle="modal" data-target="#createGroupChatModal">Tạo nhóm chat</button>
                                    </div>
                                </div>
                                <!-- Form tìm kiếm -->
                                <form method="GET" action="">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm tin nhắn..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- Chat messages here -->
                                <?php if ($messages_admin->num_rows > 0): ?>
                                    <ul class="chat-messages">
                                        <?php while ($row = $messages_admin->fetch_assoc()): ?>
                                            <?php if ($row['active'] == 1) { ?>

                                                <li class="chat-message d-flex justify-content-between align-items-center" onclick="goToChat(<?php echo $row['id']; ?>)">
                                                    <div class="media w-100 d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center flex-grow-1">
                                                            <img src="../Public/avatardefault.png" alt="<?php echo htmlspecialchars($row['sender_name']); ?>" class="avatar mr-3" />
                                                            <div class="media-body">
                                                                <h5 class="mt-0">
                                                                    <?php echo htmlspecialchars(substr($row['sender_name'], 0, 10)); ?><?php echo (strlen($row['sender_name']) > 10) ? '...' : ''; ?>, Admin,
                                                                    <?php if ($row['assigned_name']) { ?>
                                                                        <?php echo htmlspecialchars(substr($row['assigned_name'], 0, 10)); ?><?php echo (strlen($row['assigned_name']) > 10) ? '...' : ''; ?>
                                                                    <?php } else { ?>
                                                                        <span class="text-warning">Chờ chỉ định hỗ trợ</span>
                                                                    <?php } ?>
                                                                </h5>
                                                                <p><?php echo htmlspecialchars($row['last_message'] ?: $row['message']); ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- Action buttons -->
                                                        <div class="action-buttons" onclick="event.stopPropagation();">
                                                            <?php if (!$row['assigned_name']) { ?>
                                                                <select class="form-control form-control-sm mr-2" id="userSelect-<?php echo $row['id']; ?>"
                                                                    onchange="updateAssignedTo(<?php echo $row['id']; ?>, this.value, '<?php echo htmlspecialchars($row['message']); ?>', '<?php echo $row['user_id']; ?>')">
                                                                    <option value="">Chọn người hỗ trợ</option>
                                                                    <?php
                                                                    $users = $conn->query("SELECT id, username FROM users WHERE role = 1");
                                                                    while ($user = $users->fetch_assoc()):
                                                                    ?>
                                                                        <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                                                                    <?php endwhile; ?>
                                                                </select>
                                                            <?php } ?>
                                                            <button class="btn btn-sm mr-2" onclick="showSenderInfo(<?php echo $row['user_id']; ?>)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm" onclick="deleteMessage(<?php echo $row['id']; ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>

                                            <?php } else { ?>
                                                <li class="chat-message d-flex justify-content-between align-items-center">
                                                    <div class="media" onclick="location.href='chat.php?message=<?php echo $row['id']; ?>'">
                                                        <img src="../Public/avatardefault.png" alt="<?php echo htmlspecialchars($row['sender_name']); ?>" class="avatar mr-3" />
                                                        <div class="media-body">
                                                            <h5 class="mt-0">
                                                                <?php echo htmlspecialchars(substr($row['sender_name'], 0, 10)); ?><?php echo (strlen($row['sender_name']) > 10) ? '...' : ''; ?>, Admin,
                                                                <?php if ($row['assigned_name']) { ?>
                                                                    <?php echo htmlspecialchars(substr($row['assigned_name'], 0, 10)); ?><?php echo (strlen($row['assigned_name']) > 10) ? '...' : ''; ?>
                                                                <?php } else { ?>
                                                                    <span class="text-warning">Chờ chỉ định hỗ trợ</span>
                                                                <?php } ?>
                                                            </h5>
                                                            <p><?php echo htmlspecialchars($row['message']); ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- Dropdown select for users and delete button -->

                                                    <div class="d-flex align-items-center justify-content-around">


                                                        <?php if (!$row['assigned_name']) { ?>

                                                            <select class="form-control form-control-sm mr-2" id="userSelect-<?php echo $row['id']; ?>" onchange="updateAssignedTo(<?php echo $row['id']; ?>, this.value, '<?php echo htmlspecialchars($row['message']); ?>', '<?php echo $row['user_id']; ?>')">
                                                                <option value="">Chọn người hỗ trợ</option>
                                                                <?php
                                                                // Lấy danh sách user có role = 1
                                                                $users = $conn->query("SELECT id, username FROM users WHERE role = 1");
                                                                while ($user = $users->fetch_assoc()):
                                                                ?>
                                                                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                                                                <?php endwhile; ?>
                                                            </select>
                                                        <?php } ?>
                                                        <button class="btn btn-success btn-sm mr-2" onclick="showSenderInfo(<?php echo $row['user_id']; ?>)"> <i class="fas fa-eye"></i></button>
                                                        <button class="btn btn-danger btn-sm" onclick="deleteMessage(<?php echo $row['id']; ?>)"> <i class="fas fa-trash"></i></button>
                                                    </div>

                                                </li>

                                            <?php } ?>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>Chưa có tin nhắn nào.</p>
                                <?php endif; ?>
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
                            <div class="card-body">
                                <form method="GET" action="">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm tin nhắn..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- Chat messages here -->
                                <?php if ($messages_tp->num_rows > 0): ?>
                                    <ul class="chat-messages">
                                        <?php while ($row = $messages_tp->fetch_assoc()): ?>
                                            <li class="chat-message d-flex justify-content-between align-items-center">
                                                <div class="media" onclick="location.href='chat.php?message=<?php echo $row['id']; ?>'">
                                                    <img src="../Public/avatardefault.png" alt="<?php echo htmlspecialchars($row['sender_name']); ?>" class="avatar mr-3" />
                                                    <div class="media-body">
                                                        <h5 class="mt-0">
                                                            <?php echo htmlspecialchars(substr($row['sender_name'], 0, 10)); ?><?php echo (strlen($row['sender_name']) > 10) ? '...' : ''; ?>, Admin,
                                                            <?php if ($row['assigned_name']) { ?>
                                                                <?php echo htmlspecialchars(substr($row['assigned_name'], 0, 10)); ?><?php echo (strlen($row['assigned_name']) > 10) ? '...' : ''; ?>
                                                            <?php } else { ?>
                                                                <span class="text-warning">Chờ chỉ định hỗ trợ</span>
                                                            <?php } ?>
                                                        </h5>
                                                        <p><?php echo htmlspecialchars($row['message']); ?></p>
                                                    </div>
                                                </div>

                                                <!-- Dropdown select for users and delete button -->

                                                <div class="d-flex align-items-center">
                                                    <?php if (!$row['assigned_name']) { ?>

                                                        <select class="form-control form-control-sm mr-2" id="userSelect-<?php echo $row['id']; ?>" onchange="updateAssignedTo(<?php echo $row['id']; ?>, this.value, '<?php echo htmlspecialchars($row['message']); ?>', '<?php echo $row['user_id']; ?>')">
                                                            <option value="">Chọn người hỗ trợ</option>
                                                            <?php
                                                            // Lấy danh sách user có role = 1
                                                            $users = $conn->query("SELECT id, username FROM users WHERE role = 1");
                                                            while ($user = $users->fetch_assoc()):
                                                            ?>
                                                                <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    <?php } ?>

                                                    <!-- <button class="btn btn-danger btn-sm" onclick="deleteMessage(<?php echo $row['id']; ?>)">Xóa</button> -->
                                                </div>

                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>Chưa có tin nhắn nào.</p>
                                <?php endif; ?>
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
                                <!-- Chat messages here -->
                                <?php if ($messages->num_rows > 0): ?>
                                    <ul class="chat-messages">
                                        <?php while ($row = $messages->fetch_assoc()): ?>
                                            <?php if ($row['active'] == 1) { ?>
                                                <li class="chat-message" onclick="location.href='chat.php?message=<?php echo $row['id']; ?>'">
                                                    <div class="media">
                                                        <img src="../Public/avatardefault.png" alt="<?php echo htmlspecialchars($row['sender_name']); ?>" class="avatar mr-3" />
                                                        <div class="media-body">
                                                            <h5 class="mt-0">
                                                                <?php echo htmlspecialchars(substr($row['sender_name'], 0, 10)); ?><?php echo (strlen($row['sender_name']) > 10) ? '...' : ''; ?>, Admin,
                                                                <?php if ($row['assigned_name']) { ?>
                                                                    <?php echo htmlspecialchars(substr($row['assigned_name'], 0, 10)); ?><?php echo (strlen($row['assigned_name']) > 10) ? '...' : ''; ?>
                                                                <?php } else { ?>
                                                                    <span class="text-warning">Chờ chỉ định hỗ trợ</span>
                                                                <?php } ?>
                                                            </h5>
                                                            <p><?php echo htmlspecialchars($row['message']); ?></p>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>Chưa có tin nhắn nào.</p>
                                <?php endif; ?>
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
            max-height: 500px;
            /* Chiều cao cố định cho khung tin nhắn */
            overflow-y: auto;
            /* Cuộn khi đầy */
            margin-bottom: 20px;
            /* Khoảng cách dưới cùng */
            flex-grow: 1;
            /* Để chiếm không gian còn lại */
        }

        .chat-message {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.2s ease;
            border: 1px solid #eef2f7;
            background: #fff;
            cursor: pointer;
        }

        /* Tin nhắn đang hoạt động */
        .chat-message[style*="background-color: #3794ff"] {
            background: linear-gradient(145deg, #f8f9fa, #e9ecef) !important;
            border: 1px solid #dee2e6;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .chat-message:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            background: #f8f9fa;
        }

        /* Thêm hiệu ứng khi click */
        .chat-message:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .media-body h5 {
            font-size: 0.95rem;
            color: #495057;
            margin-bottom: 4px;
        }

        .media-body .text-muted {
            font-size: 0.8rem;
        }

        .media-body p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Style cho các nút action */
        .action-buttons .btn {
            background: #f8f9fa;
            border: 1px solid #e0e4e8;
            color: #6c757d;
            /* Màu xám cho icon */
            padding: 6px 10px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .action-buttons .btn:hover {
            background: #e9ecef;
            color: #495057;
            /* Màu xám đậm hơn khi hover */
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }

        .action-buttons .btn:active {
            transform: translateY(0);
            background: #e2e6ea;
        }

        /* Loại bỏ màu gốc của các nút */
        .action-buttons .btn-success,
        .action-buttons .btn-danger {
            background: #f8f9fa !important;
            border-color: #e0e4e8 !important;
        }

        .action-buttons .btn-success:hover,
        .action-buttons .btn-danger:hover {
            background: #e9ecef !important;
        }

        /* Đảm bảo icon luôn có màu xám */
        .action-buttons .btn i {
            color: #6c757d !important;
        }

        /* Điều chỉnh layout cho action buttons */
        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 15px;
            white-space: nowrap;
            /* Ngăn các nút xuống dòng */
            flex-shrink: 0;
            /* Ngăn co lại khi không đủ không gian */
        }

        /* Điều chỉnh dropdown */
        .action-buttons select.form-control-sm {
            width: auto;
            /* Cho phép select box co lại theo nội dung */
            min-width: 150px;
            /* Đảm bảo độ rộng tối thiểu */
            flex-shrink: 1;
            /* Cho phép co lại nếu cần */
        }

        /* Media container */
        .media.w-100 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            min-width: 0;
            /* Cho phép text overflow hoạt động */
        }

        /* Content container */
        .d-flex.align-items-center.flex-grow-1 {
            min-width: 0;
            /* Cho phép text overflow hoạt động */
            margin-right: 15px;
        }

        /* Text content */
        .media-body {
            min-width: 0;
            /* Cho phép text overflow hoạt động */
        }

        .media-body h5,
        .media-body p {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Buttons */
        .action-buttons .btn {
            padding: 6px 10px;
            flex-shrink: 0;
            /* Ngăn nút bị co lại */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .action-buttons select.form-control-sm {
                min-width: 120px;
                /* Giảm độ rộng tối thiểu trên mobile */
            }

            .media-body h5 {
                font-size: 0.9rem;
            }

            .media-body p {
                font-size: 0.85rem;
            }
        }
    </style>

    <!-- /.content -->
</div>

<!-- Modal để hiển thị thông tin người gửi -->
<div class="modal fade" id="senderInfoModal" tabindex="-1" aria-labelledby="senderInfoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="senderInfoLabel">Thông tin người gửi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="senderInfoBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal tạo nhóm chat -->
<div class="modal fade" id="createGroupChatModal" tabindex="-1" aria-labelledby="createGroupChatLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createGroupChatLabel">Tạo Nhóm Chat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Chọn nhân viên để thêm vào nhóm:</h6>
                <ul class="list-group" id="employeeList">
                    <?php
                    // Lấy danh sách nhân viên từ cơ sở dữ liệu
                    $employees = $conn->query("SELECT id, username FROM users"); // Giả sử role = 1 là nhân viên
                    while ($employee = $employees->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <input type="checkbox" class="employee-checkbox" value="<?php echo $employee['id']; ?>">
                            <?php echo htmlspecialchars($employee['username']); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="createGroupChatButton">Tạo nhóm chat</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<script>
    function showSenderInfo(userId) {
        // Gửi yêu cầu AJAX để lấy thông tin chi tiết của người gửi
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_user_info.php?user_id=" + userId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Đổ thông tin nhận được vào modal body
                    document.getElementById('senderInfoBody').innerHTML = xhr.responseText;
                    // Hiển thị modal
                    var senderInfoModal = new bootstrap.Modal(document.getElementById('senderInfoModal'), {});
                    senderInfoModal.show();
                } else {
                    alert('Lỗi khi tải thông tin người gửi.');
                }
            }
        };
        xhr.send();
    }

    function updateAssignedTo(messageId, userId, messageContent, senderId) {
        console.log(messageId, userId, messageContent, senderId);

        if (userId) {
            // Gửi yêu cầu AJAX để cập nhật assigned_to
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_assigned.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Chỉ định thành công!");
                        location.reload();
                    } else {
                        alert("Lỗi: " + response.error);
                    }
                }
            };
            xhr.send("message_id=" + messageId + "&assigned_to=" + userId + "&message_content=" + encodeURIComponent(messageContent) + "&sender_id=" + senderId);
        }
    }

    function deleteMessage(messageId) {
        if (confirm("Bạn có chắc chắn muốn xóa tin nhắn này?")) {
            // Gửi yêu cầu AJAX để xóa tin nhắn
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_message.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Bạn đã xóa tin nhắn!");
                        location.reload(); // Tải lại trang để cập nhật
                    } else {
                        alert("Lỗi: " + response.error);
                    }
                }
            };
            xhr.send("message_id=" + messageId);
        }
    }

    function goToChat(messageId) {
        window.location.href = 'chat.php?message=' + messageId;
    }

    // Ngăn chặn sự kiện click từ các phần tử con
    document.querySelectorAll('.action-buttons').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    document.getElementById('createGroupChatButton').addEventListener('click', function() {
        const selectedEmployees = Array.from(document.querySelectorAll('.employee-checkbox:checked')).map(checkbox => checkbox.value);

        if (selectedEmployees.length > 0) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "create_group_chat.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        location.reload(); // Tải lại trang để cập nhật
                    } else {
                        alert("Lỗi: " + response.error);
                    }
                }
            };
            const employeeIds = selectedEmployees.join(',');
            xhr.send("user_id=" + <?php echo $user_id; ?> + "&assigned_to=" + employeeIds);

        } else {
            alert('Vui lòng chọn ít nhất một nhân viên.');
        }
    });
</script>
<!-- /.content-wrapper -->
<?php require 'footer.php'; ?>

<style>
    .chat-messages {
        list-style-type: none;
        padding: 0;
    }

    .chat-message {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        transition: background 0.3s;
    }

    .chat-message:hover {
        background: #f0f0f0;
    }

    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
</style>