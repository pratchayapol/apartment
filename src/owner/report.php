<?php
session_start();
include "../config_db.php";
// ปิดการแสดงข้อผิดพลาด
// error_reporting(0);
// ini_set('display_errors', 0);

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
</head>

<body>
    <!-- Navbar -->
    <?php include "plugin/menu.php"; ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1>รายงาน</h1>
                

                <!-- Card for Room Management -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">สรุปรายงานค่าน้ำ ไฟ และยอดรวมค่าห้อง</h5>
                        <p class="card-text">3 เดือนย้อนหลัง</p>
                        <a href="report_3m" class="btn btn-primary">รายงาน</a>
                    </div>
                </div>

                <!-- Card for Reports -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">สรุปรายงานค่าน้ำ ไฟ และยอดรวมค่าห้อง</h5>
                        <p class="card-text">6 เดือนย้อนหลัง</p>
                        <a href="report_6m" class="btn btn-primary">รายงาน</a>
                    </div>
                </div>

                <!-- Card for Settings -->
                
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS (Optional, for features like modals or tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
