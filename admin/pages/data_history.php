<?php require 'header.php';
require_once "../src/function.php";
$nv = $conn->query("SELECT * FROM nhan_vien");
$manv = $_SESSION['Ma_nv'];
$today = date("d/m/Y");
$sql = "SELECT Ngay, so_gio_thieu FROM cham_cong WHERE Ma_nv = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $manv);
$stmt->execute();
$result = $stmt->get_result();

$workingDays = [];
$missingHours = [];

while ($row = $result->fetch_assoc()) {
    //   $workingDays[] = date('Y-m-d', strtotime($row['Ngay'])); // Định dạng ngày
    $formattedDate = date('Y-m-d', strtotime($row['Ngay'])); // Định dạng ngày
    $workingDays[] = $formattedDate;
    $missingHours[$formattedDate] = $row['so_gio_thieu']; // Lưu số giờ thiếu theo ngày
}


$stmt->close();
$conn->close();




?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <span style="color:red; font-weight:bold" class="ml-5">Lịch sử chấm công</span>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right"><b>
                            <?php
                            echo $today;
                            ?> <span id="timer"></span></b>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <style>
            .calendar {
                margin-top: 20px;
            }

            .day {
                min-height: 100px;
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
            }

            .header {
                background-color: #007bff;
                color: white;
                padding: 10px 0;
            }

            .nav-button {
                cursor: pointer;
                margin: 0 10px;
                padding: 10px 20px;
                border-radius: 5px;
                background-color: #007bff;
                color: white;
                transition: background-color 0.3s;
            }

            .nav-button:hover {
                background-color: #0056b3;
            }

            .dot {
                height: 10px;
                width: 10px;
                background-color: green;
                border-radius: 50%;
                position: absolute;
                top: 5px;
                left: 5px;
            }

            .worked-day {
                background-color: lightgreen;
                /* Màu nền cho ngày đã làm việc */
            }
        </style>
        </style>
        <div class="container-fluid">
            <div class="container">
                <div class="text-center mb-3">
                    <span class="nav-button" id="prevMonth">&#10094; Tháng trước</span>
                    <span class="nav-button" id="nextMonth">Tháng sau &#10095;</span>
                </div>
                <div class="calendar" id="calendar"></div>
            </div>

            <script>
                let currentDate = new Date();
                const workingDays = <?php echo json_encode($workingDays); ?>; // Ngày làm việc từ PHP
                const missingHours = <?php echo json_encode($missingHours); ?>; // Số giờ thiếu từ PHP


                function createCalendar() {
                    const calendarElement = document.getElementById('calendar');
                    calendarElement.innerHTML = ''; // Xóa lịch cũ

                    const year = currentDate.getFullYear();
                    const month = currentDate.getMonth();

                    // Tiêu đề tháng và năm
                    const header = document.createElement('div');
                    header.className = 'row';
                    header.innerHTML = `<div class="col-12 text-center header">${currentDate.toLocaleString('default', { month: 'long' })} ${year}</div>`;
                    calendarElement.appendChild(header);

                    // Các ngày trong tuần
                    const daysRow = document.createElement('div');
                    daysRow.className = 'row';
                    const days = ['CN', 'Th 2', 'Th 3', 'Th 4', 'Th 5', 'Th 6', 'Th 7'];
                    days.forEach(day => {
                        const dayElement = document.createElement('div');
                        dayElement.className = 'col day';
                        dayElement.innerText = day;
                        daysRow.appendChild(dayElement);
                    });
                    calendarElement.appendChild(daysRow);

                    // Ngày đầu tháng
                    const firstDay = new Date(year, month, 1).getDay();
                    const lastDate = new Date(year, month + 1, 0).getDate();
                    const lastMonthLastDate = new Date(year, month, 0).getDate(); // Ngày cuối tháng trước

                    // Thêm các khoảng trống cho các ngày trước tháng
                    let daysInMonthRow = document.createElement('div');
                    daysInMonthRow.className = 'row';

                    // Thêm các ngày của tháng trước (nếu có)
                    for (let i = firstDay; i > 0; i--) {
                        const emptyDay = document.createElement('div');
                        emptyDay.className = 'col day';
                        emptyDay.innerText = lastMonthLastDate - i + 1; // Ngày cuối tháng trước
                        daysInMonthRow.appendChild(emptyDay);
                    }

                    // Thêm các ngày trong tháng
                    for (let date = 1; date <= lastDate; date++) {
                        const dayElement = document.createElement('div');
                        dayElement.className = 'col day';
                        dayElement.innerText = date;

                        // Kiểm tra ngày làm việc
                        const fullDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                        if (workingDays.includes(fullDate)) {
                            const dot = document.createElement('div');
                            dot.className = 'dot';
                            dayElement.appendChild(dot); // Thêm dấu chấm vào ngày
                            dayElement.classList.add('worked-day'); // Thêm lớp màu xanh lá cây

                            // Thêm số giờ thiếu
                            const hoursMissing = missingHours[fullDate] || 0; // Lấy số giờ thiếu
                            const missingHoursElement = document.createElement('div');
                            if (hoursMissing > 0) {
                                missingHoursElement.innerText = `thiếu ${hoursMissing} giờ`;
                                missingHoursElement.style.fontSize = 'small'; // Đặt cỡ chữ nhỏ
                                missingHoursElement.style.position = 'absolute'; // Đặt vị trí tuyệt đối
                                missingHoursElement.style.right = '5px'; // Căn phải
                                missingHoursElement.style.bottom = '5px'; // Căn dưới
                                dayElement.appendChild(missingHoursElement); // Thêm vào ngày
                            }
                            if (hoursMissing > 0) {
                                dayElement.classList.add('worked-day'); // Thêm lớp nếu có giờ thiếu
                                dayElement.style.backgroundColor = 'lightcoral'; // Nền màu đỏ nếu có giờ thiếu
                            } else {
                                dayElement.classList.add('worked-day'); // Thêm lớp nếu không có giờ thiếu
                                dayElement.style.backgroundColor = 'lightgreen'; // Nền màu xanh nếu không có giờ thiếu
                            }
                        }

                        daysInMonthRow.appendChild(dayElement);

                        // Nếu đến cuối tuần, tạo một hàng mới
                        if ((firstDay + date) % 7 === 0) {
                            calendarElement.appendChild(daysInMonthRow);
                            daysInMonthRow = document.createElement('div');
                            daysInMonthRow.className = 'row';
                        }
                    }

                    // Thêm các khoảng trống cho các ngày của tháng sau (nếu cần)
                    const remainingDays = (7 - (lastDate + firstDay) % 7) % 7;
                    for (let i = 1; i <= remainingDays; i++) {
                        const emptyDay = document.createElement('div');
                        emptyDay.className = 'col day';
                        emptyDay.innerText = i; // Ngày tháng sau
                        daysInMonthRow.appendChild(emptyDay);
                    }

                    // Đảm bảo rằng hàng cuối cùng được thêm vào
                    calendarElement.appendChild(daysInMonthRow);
                }

                document.getElementById('prevMonth').onclick = function() {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    createCalendar();
                };

                document.getElementById('nextMonth').onclick = function() {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    createCalendar();
                };

                // Khởi tạo lịch
                createCalendar();
            </script>
        </div>
    </section>

    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

<?php require 'footer.php' ?>