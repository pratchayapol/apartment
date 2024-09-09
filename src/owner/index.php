<?php
session_start();
include "../config_db.php";

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
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
                <h1>ยินดีต้อนรับสู่ระบบดูแลหอพัก</h1>
                <p>ในหน้านี้คุณสามารถดูข้อมูลสรุปของหอพัก, รายงานการจ่ายเงิน, และจัดการห้องพักได้</p>

                <!-- Card for Room Management -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">การจัดการห้องพัก</h5>
                        <p class="card-text">ตรวจสอบและจัดการห้องพักทั้งหมดของคุณที่นี่</p>
                        <a href="manager" class="btn btn-primary">ไปที่การจัดการห้องพัก</a>
                    </div>
                </div>

                <!-- Card for Reports -->
                <!-- <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">รายงาน</h5>
                        <p class="card-text">ดูรายงานการจ่ายเงินและข้อมูลสำคัญอื่นๆ ได้ที่นี่</p>
                        <a href="report" class="btn btn-primary">ไปที่รายงาน</a>
                    </div>
                </div> -->

                <!-- Card for Settings -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">ตั้งค่า</h5>
                        <p class="card-text">ปรับแต่งการตั้งค่าและข้อมูลส่วนตัวของคุณที่นี่</p>
                        <a href="setting" class="btn btn-primary">ไปที่การตั้งค่า</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS (Optional, for features like modals or tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
