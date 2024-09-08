<?php
session_start();
include "../config_db.php";

if (isset($_GET['id_room'])) {
    $id_room = $_GET['id_room'];
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจัดการห้องพัก</title>
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
    <?php
    // ดึงข้อมูลทั้งหมดจากตาราง room
    $sql = "SELECT * FROM room WHERE id_room = $id_room";
    $result = $conn->query($sql);
    ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1>การจัดการห้องพัก</h1>
                <!-- <p>ในหน้านี้คุณสามารถเลือกห้องเพื่อแก้ไขข้อมูล</p> -->

                <div class="row">
                    <?php
                    if ($result->num_rows > 0) {
                        $count = 0;

                        $row = $result->fetch_assoc();

                        $id_tenant = $row['id_tenant'];

                        // ข้อมูลผู้เช่า
                        $sql1 = "SELECT * FROM tenant WHERE id_tenant = $id_tenant";
                        $result1 = $conn->query($sql1);

                        $row1 = $result1->fetch_assoc();
                        $full_name = $row1['first_name'] . '  ' . $row1['last_name'] . ' ( ' . $row1['tel'] . ' )';



                        // ข้อมูลมิเตอร์น้ำ
                    ?>
                        <div class="col-md-12">
                            <div class="card text-white bg-success mb-12">
                                <div class="card-header">ข้อมูลห้อง <?= $row['number_room']; ?></div>
                                <div class="card-body">
                                    <p class="card-text"> - ผู้เช่าห้อง : <?= $full_name ?></p>
                                    <p class="card-text"> - เลขมิเตอร์น้ำ : <?= $full_name ?></p>
                                    <p class="card-text"> - เลขมิเตอร์ไฟฟ้า : <?= $full_name ?></p>
                                </div>
                            </div>
                        <?php


                    }
                        ?>
                        </div> <!-- ปิด row -->
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS (Optional, for features like modals or tooltips) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>