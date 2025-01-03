<!DOCTYPE html>
<html>
<head>
<title>Tạo biểu đồ sử dụng PHP và Chart.js</title>
<style type="text/css">
BODY {
    width: 550PX;
}

#chart-container {
    width: 100%;
    height: auto;
}
</style>
<script type="text/javascript" src="../Public/admin/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../Public/admin/plugins/chart.js/Chart.min.js"></script>


</head>
<body>
    <div id="chart-container">
        <canvas id="graph"></canvas>
    </div>

    <script>
        $(document).ready(function () {
            showGraph();
        });

        function showGraph(){
        
            $.post("data.php",
                function (data){
                    console.log(data);
                    var formStatusVar = [];
                    var total = []; 

                    for (var i in data) {
                        formStatusVar.push(data[i].Tong);
                        total.push(data[i].Tong);
                    }

                    var options = {
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                display: true
                            }],
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    };

                    var myChart = {
                        labels: formStatusVar,
                        datasets: [
                            {
                                label: 'Tổng số',
                                backgroundColor: '#17cbd1',
                                borderColor: '#46d5f1',
                                hoverBackgroundColor: '#0ec2b6',
                                hoverBorderColor: '#42f5ef',
                                data: total
                            }
                        ]
                    };

                    var bar = $("#graph"); 
                    var barGraph = new Chart(bar, {
                        type: 'bar',
                        data: myChart,
                        options: options
                    });
                });
        }
    </script>
</body>
</html>