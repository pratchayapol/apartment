<?php
session_start();
include "../config_db.php";

// ปิดการแสดงข้อผิดพลาด
// error_reporting(0);
// ini_set('display_errors', 0);

$sql_3_months = "
    SELECT
        DATE_FORMAT(rental_timestam, '%Y-%m') AS month,
        SUM(water_bill) AS total_water_bill,
        SUM(electricity_bill) AS total_electricity_bill,
        SUM(net) AS total_net
    FROM rental
    WHERE rental_timestam BETWEEN NOW() - INTERVAL 2 MONTH AND NOW()
    GROUP BY DATE_FORMAT(rental_timestam, '%Y-%m')
    ORDER BY month
";

$result_3_months = $conn->query($sql_3_months);

$data = [];
if ($result_3_months) {
    while ($row = $result_3_months->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

$months = [];
$water_bills = [];
$electricity_bills = [];
$net_costs = [];

foreach ($data as $row) {
    $months[] = $row['month'];
    $water_bills[] = (float) $row['total_water_bill'];
    $electricity_bills[] = (float) $row['total_electricity_bill'];
    $net_costs[] = (float) $row['total_net'];
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงาน</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
        }

        .pdf-icons {
            display: flex;
            gap: 10px;
            /* ระยะห่างระหว่างไอคอน */
            flex-wrap: wrap;
            /* จัดให้แถวต่อไปถ้ามีไอคอนเยอะเกิน */
        }

        .pdf-icon {
            text-decoration: none;
            color: white;
            /* สีของไอคอน */
        }

        .pdf-icon i {
            margin-right: 5px;
            /* ระยะห่างระหว่างไอคอนกับข้อความ */
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@latest/dist/jspdf.plugin.autotable.min.js"></script>

</head>

<body>
    <!-- Navbar -->
    <?php include "plugin/menu.php"; ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card text-back">
                    <div class="card-header">3-Month Report</div>
                    <div class="card-body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <button id="pdfButton" class="btn btn-primary">Download PDF</button>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                        label: 'ค่าน้ำรวม',
                        data: <?php echo json_encode($water_bills); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'ค่าไฟรวม',
                        data: <?php echo json_encode($electricity_bills); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'ค่าสุทธิ',
                        data: <?php echo json_encode($net_costs); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        document.getElementById('pdfButton').addEventListener('click', function() {
            if (typeof window.jspdf === 'undefined') {
                console.error('jsPDF not loaded');
                return;
            }
            
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text('3-Month Report', 14, 16);

            // Add chart as an image
            var canvas = document.getElementById('myChart');
            var imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 14, 30, 180, 90); // Adjust position and size as needed

            // Add table data
            doc.autoTable({
                head: [['Month', 'Total Water Bill', 'Total Electricity Bill', 'Total Net']],
                body: <?php echo json_encode(array_map(function($row) { return [$row['month'], $row['total_water_bill'], $row['total_electricity_bill'], $row['total_net']]; }, $data)); ?>,
                startY: 130,
                theme: 'striped',
            });

            doc.save('report_3m.pdf');
        });
    </script>
</body>

</html>
