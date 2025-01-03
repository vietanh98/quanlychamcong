 <!-- /.content-wrapper -->

 <footer class="main-footer">
     <strong>Quản lý chấm công</strong>
     <div class="float-right d-none d-sm-inline-block">
         <b>Version</b> 3.2.0
     </div>
 </footer>

 <!-- Icon chat -->
 <?php
    //     $connectionClosed = false;
    //     if ($_SESSION['role'] == 'employee') {
    //         $sqlusers = "SELECT id, username FROM users";
    //         $resultusers = $conn->query($sqlusers);
    //         $users = [];

    //         if ($resultusers->num_rows > 0) {
    //             // Lấy từng hàng dữ liệu
    //             while ($row = $resultusers->fetch_assoc()) {
    //                 $users[] = $row;
    //             }
    //             $connectionClosed = false;
    //         }

    //         // Truy vấn lấy các tin nhắn mẫu từ bảng message_sample
    //         // $sqlMessageSample = "SELECT * FROM message_sample";
    //         // $resultMessageSample = $conn->query($sqlMessageSample);

    //         // $messageSamples = [];

    //         // if ($resultMessageSample->num_rows > 0) {
    //         //     while ($row = $resultMessageSample->fetch_assoc()) {
    //         //         $messageSamples[] = $row;
    //         //     }
    //         // }
    //     
    ?>
 <!-- Hiển thị nút chat -->
 <!-- <button class="btn btn-primary rounded-circle chat-icon" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; width: 50px; height: 50px;">
     <i class="fas fa-comment-dots" style="font-size: 20px; color: white;"></i>
      </button> -->
 <?php
    //         if (!$connectionClosed) {
    //             $conn->close();
    //         }
    //     }
    ?>
 <!-- Popup tìm kiếm người dùng -->
 <div id="chatPopup" class="card" style="display: none; position: fixed; bottom: 70px; right: 20px; width: 350px; z-index: 1001;">
     <div style="background: #bf3b3b;" class="card-header custom-card-header text-light">
         <div class="row justify-content-around">
             <h5 class="mb-0 fw-bold">Bạn muốn trò chuyện với</h5>
             <button type="button" class="close ml-5" id="closeChat">
                 <span style="color:#fff">&times;</span>
             </button>
         </div>
         <div style="font-size: 15px;" class="col pt-2">
             <span>Vui lòng liên hệ admin để được hỗ trợ</span>
         </div>

     </div>
     <div class="card-body">
         <div class="mb-4">
             <h5>Cuộc trò chuyện</h5>
             <div class="d-flex align-items-start">
                 <img src="../Public/icon_chat.jpg" alt="" class="rounded-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                 <div>
                     <p class="mb-1"><strong>Chào mừng bạn...</strong></p>
                     <p class="text-muted mb-0">Nhập thông tin cần hỗ trợ nào</p>
                 </div>
             </div>
         </div>
         <div class="border rounded mb-2" style="height: 200px; overflow-y: auto; padding:10px">

             <div id="chatMessages" style="max-height: 200px;"></div>
         </div>

         <div class="input-group mb-3 ">
             <!-- <input type="text" class="form-control" id="messageInputUser" placeholder="Nhập tin nhắn...">
            <div class="input-group-append">
                <button style="background: #bf3b3b;" class="btn btn-outline-secondary" id="sendMessageBtn">
                    <i style="color: white;" class="fas fa-paper-plane"></i>
                </button>
            </div> -->
             <button style="background: #bf3b3b; color: #fff; width: 100%" class="btn btn-outline-secondary" id="sendMessageBtn"> Bắt đầu
                 <i style="color: white;" class="fas fa-paper-plane"></i>
             </button>
             <button style=" color: #fff; width: 100%; display:none" class="btn btn-primary btn-outline-primary" id="sendSelectedMessages"> Gửi
                 <i style="color: white;" class="fas fa-paper-plane"></i>
             </button>
         </div>
         <!-- <div id="suggestions" style="max-height: 150px; overflow-y: auto;"></div> -->
     </div>

 </div>

 <!-- JavaScript -->
 <script>
     // // Hiện popup khi nhấn vào icon chat
     // document.querySelector('.chat-icon').addEventListener('click', function() {
     //     const popup = document.getElementById('chatPopup');
     //     popup.style.display = (popup.style.display === 'none' || popup.style.display === '') ? 'block' : 'none';
     // });

     // Ẩn popup khi nhấn nút Đóng
     document.getElementById('closeChat').addEventListener('click', function() {
         document.getElementById('chatPopup').style.display = 'none';
     });


     const messageSamples = "";

     // Xử lý khi người dùng nhấn nút 'sendMessageBtn'
     document.getElementById('sendMessageBtn').addEventListener('click', function() {
         const chatMessages = document.getElementById('chatMessages');
         const startMessageBtn = document.getElementById('sendMessageBtn');
         const sendMessageBtn = document.getElementById('sendSelectedMessages');

         startMessageBtn.style.display = 'none';
         sendMessageBtn.style.display = 'block';
         chatMessages.innerHTML = ''; // Xóa nội dung cũ nếu có

         messageSamples.forEach(sample => {
             // Tạo các phần tử hiển thị tin nhắn mẫu với checkbox
             const sampleContainer = document.createElement('div');
             sampleContainer.classList.add('sample-message', 'd-flex', 'align-items-center');

             const sampleCheckbox = document.createElement('input');
             sampleCheckbox.type = 'checkbox';
             sampleCheckbox.classList.add('sample-checkbox');
             sampleCheckbox.value = sample.content;

             const sampleText = document.createElement('span');
             sampleText.classList.add('ml-2');
             sampleText.textContent = sample.content;

             sampleContainer.appendChild(sampleCheckbox);
             sampleContainer.appendChild(sampleText);
             chatMessages.appendChild(sampleContainer);
         });
     });

     document.getElementById('sendSelectedMessages').addEventListener('click', function() {
         const selectedMessages = Array.from(document.querySelectorAll('.sample-checkbox:checked')).map(cb => cb.value);

         if (selectedMessages.length === 0) {
             alert('Vui lòng chọn ít nhất một tin nhắn để gửi.');
             return;
         }

         const userId = <?php echo $_SESSION['id']; ?>;

         fetch('save_message.php', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                 },
                 body: JSON.stringify({
                     messages: selectedMessages,
                     userId: userId
                 }),
             })
             .then(response => response.json())
             .then(data => {
                 if (data.success) {
                     const chatMessages = document.getElementById('chatMessages');

                     // Hiển thị từng tin nhắn đã gửi bên phải với màu xanh
                     selectedMessages.forEach(message => {
                         const messageElement = document.createElement('div');
                         messageElement.classList.add('user-message');
                         messageElement.textContent = message;
                         messageElement.style.alignSelf = 'flex-end'; // Đẩy tin nhắn sang bên phải
                         chatMessages.appendChild(messageElement);
                     });

                     // Thêm thông báo "Chờ admin chỉ định người hỗ trợ" bên trái với màu xanh đậm
                     const waitingMessage = document.createElement('div');
                     waitingMessage.classList.add('admin-message');
                     waitingMessage.textContent = "Chờ admin chỉ định người hỗ trợ...";
                     waitingMessage.style.alignSelf = 'flex-start'; // Đẩy thông báo sang bên trái
                     chatMessages.appendChild(waitingMessage);

                     // Xóa tất cả các tin nhắn gợi ý
                     document.querySelectorAll('.sample-message').forEach(sample => sample.remove());

                     // alert("Tin nhắn đã được gửi cho admin.");
                 } else {
                     alert("Lỗi khi gửi tin nhắn: " + data.error);
                 }
             })
             .catch(error => console.error('Error:', error));
     });
 </script>




 <!-- CSS -->
 <style>
     .suggestion-item {
         cursor: pointer;
     }

     .suggestion-item:hover {
         background-color: #f8f9fa;
     }

     .custom-card-header {
         border-bottom: 1px solid #ccc;
         /* Màu của đường viền */
         width: 99%;
         /* Chiều rộng của đường viền */
         margin: 0 auto;
         /* Căn giữa */
     }

     .card-body img {
         border: 2px solid #007bff;
         /* Đường viền cho hình ảnh */
     }

     .card-body p {
         margin: 0;
         /* Bỏ margin cho các thẻ p */
     }

     #chatMessages div {
         background-color: #e9ecef;
         border-radius: 5px;
         margin-bottom: 5px;
     }

     .bg-info {
         background-color: #17a2b8 !important;
         /* Màu nền cho tin nhắn admin */
     }

     .text-light {
         color: white !important;
         /* Màu chữ cho tin nhắn admin */
     }

     /* Tin nhắn của người gửi */
     .user-message {
         background-color: #e9ecef !important;
         /* Màu nền cho tin nhắn của bạn */
         border-radius: 10px;
         margin-bottom: 10px;
         padding: 10px;
         max-width: 70%;
         /* Giới hạn chiều rộng */
         align-self: flex-end;
         /* Đẩy sang bên phải */
     }

     /* Tin nhắn trả lời từ admin */
     .admin-message {
         background-color: #17a2b8 !important;
         /* Màu nền cho tin nhắn admin */
         color: white;
         /* Màu chữ cho tin nhắn admin */
         border-radius: 10px;
         margin-bottom: 10px;
         padding: 10px;
         max-width: 70%;
         /* Giới hạn chiều rộng */
         align-self: flex-start;
         /* Đẩy sang bên trái */
     }

     /* Thiết lập flexbox cho khu vực tin nhắn */
     #chatMessages {
         display: flex;
         flex-direction: column;
         align-items: flex-start;
         /* Mặc định tin nhắn nằm bên trái */
     }
 </style>
 <!-- Control Sidebar -->
 <aside class="control-sidebar control-sidebar-dark">
     <!-- Control sidebar content goes here -->
 </aside>
 <!-- /.control-sidebar -->
 </div>
 <!-- ./wrapper -->

 <!-- jQuery -->
 <script src="../Public/admin/plugins/jquery/jquery.min.js"></script>
 <!-- jQuery UI 1.11.4 -->
 <script src="../Public/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
 <script>
     $.widget.bridge('uibutton', $.ui.button)
 </script>
 <!-- Bootstrap 4 -->
 <script src="../Public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- ChartJS -->
 <script src="../Public/admin/plugins/chart.js/Chart.min.js"></script>
 <!-- Sparkline -->
 <!-- <script src="../Public/admin/plugins/sparklines/sparkline.js"></script> -->
 <!-- JQVMap -->
 <script src="../Public/admin/plugins/jqvmap/jquery.vmap.min.js"></script>
 <script src="../Public/admin/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
 <!-- jQuery Knob Chart -->
 <script src="../Public/admin/plugins/jquery-knob/jquery.knob.min.js"></script>
 <!-- daterangepicker -->
 <script src="../Public/admin/plugins/moment/moment.min.js"></script>
 <script src="../Public/admin/plugins/daterangepicker/daterangepicker.js"></script>
 <!-- Tempusdominus Bootstrap 4 -->
 <script src="../Public/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
 <!-- Summernote -->
 <script src="../Public/admin/plugins/summernote/summernote-bs4.min.js"></script>
 <!-- overlayScrollbars -->
 <script src="../Public/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
 <!-- AdminLTE App -->
 <script src="../Public/admin/dist/js/adminlte.js"></script>
 <!-- AdminLTE for demo purposes -->
 <script src="../Public/admin/dist/js/demo.js"></script>
 <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
 <!-- <script src="../Public/admin/dist/js/pages/dashboard.js"></script> -->
 </body>

 </html>