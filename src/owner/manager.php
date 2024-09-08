<?php
session_start();
include "../config_db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $room_code = $_POST['room_code'];
    $water_meter = $_POST['water_meter'];
    $electric_meter = $_POST['electric_meter'];
    $tenant = $_POST['tenant'];
    $room_status = $_POST['room_status'];



    // เริ่มทำ Transaction เพื่อให้การ insert ข้อมูลเป็น Atomic
    $conn->begin_transaction();

    try {
        // Insert ข้อมูลเข้าตาราง water_meter
        $sql1 = "INSERT INTO water_meter (number_room, number_water_meter, save_water_meter) VALUES (?, ?, NOW())";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("si", $room_code, $water_meter);
        $stmt1->execute();
        $id_water_meter = $conn->insert_id;  // ดึง id ของ water_meter ล่าสุด

        // Insert ข้อมูลเข้าตาราง electricity_meter
        $sql2 = "INSERT INTO electricity_meter (number_room, number_electricity_meter, save_electricity_meter) VALUES (?, ?, NOW())";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("si", $room_code, $electric_meter);
        $stmt2->execute();
        $id_electricity_meter = $conn->insert_id;  // ดึง id ของ electricity_meter ล่าสุด

        // Insert ข้อมูลเข้าตาราง room โดยใช้ FK จาก id_water_meter และ id_electricity_meter ที่เพิ่งได้มา
        $sql3 = "INSERT INTO room (number_room, id_electricity_meter, id_water_meter, id_tenant, status_room) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("siiis", $room_code, $id_electricity_meter, $id_water_meter, $tenant, $room_status);
        $stmt3->execute();

        // Commit transaction
        $conn->commit();
        ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">เพิ่มห้องสำเร็จ</div>',
                    icon: 'success',
                    confirmButtonText: '<div class="text t1">ตกลง</div>',
                    allowOutsideClick: false, // Disable clicking outside popup to close
                    allowEscapeKey: false, // Disable ESC key to close
                    allowEnterKey: false // Disable Enter key to close
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "manager";
                    }
                });
            }, 1000); // Adjust timeout duration if needed
        </script>
    <?php
    } catch (Exception $e) {
        // ถ้ามีข้อผิดพลาดเกิดขึ้น ยกเลิกการทำงานทั้งหมด (rollback)
        $conn->rollback();
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn->close();
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


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


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
                <h1>การจัดการห้องพัก</h1>
                <p>ในหน้านี้คุณสามารถเลือกห้องเพื่อแก้ไขข้อมูล</p>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRoomModal">
                        เพิ่มข้อมูลห้องพัก
                    </button>
                </div><br>

                <!-- Popup Modal Form -->
                <div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addRoomModalLabel">เพิ่มข้อมูลห้องพัก</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="addRoomForm" method="POST" action="">
                                    <div class="form-group">
                                        <label for="room_code">รหัสห้อง</label>
                                        <input type="text" class="form-control" id="room_code" name="room_code" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="water_meter">เลขมิเตอร์น้ำล่าสุด</label>
                                        <input type="number" class="form-control" id="water_meter" name="water_meter" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="electric_meter">เลขมิเตอร์ไฟล่าสุด</label>
                                        <input type="number" class="form-control" id="electric_meter" name="electric_meter" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tenant">ผู้เช่า</label>
                                        <select class="form-control" id="tenant" name="tenant" required>
                                            <option value="">เลือกผู้เช่า</option>
                                            <?php
                                            // ดึงข้อมูลผู้เช่าจากฐานข้อมูล
                                            $sql1 = "SELECT * FROM tenant";
                                            $result1 = $conn->query($sql1);

                                            // ตรวจสอบว่ามีผลลัพธ์หรือไม่
                                            if ($result1->num_rows > 0) {
                                                // Loop ข้อมูลผู้เช่า
                                                while ($row1 = $result1->fetch_assoc()) {
                                                    // สร้างตัวเลือกโดยรวมชื่อและเบอร์โทรศัพท์
                                                    $full_name = $row1['first_name'] . ' ' . $row1['last_name'] . ' ( ' . $row1['tel'] . ' )';
                                                    echo '<option value="' . $row1['id_tenant'] . '">' . $full_name . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">ไม่มีข้อมูลผู้เช่า</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="room_status">สถานะห้อง</label>
                                        <select class="form-control" id="room_status" name="room_status" required>
                                            <option value="ว่าง">ว่าง</option>
                                            <option value="ไม่ว่าง">ไม่ว่าง</option>
                                            <option value="กำลังเช่าอยู่">กำลังเช่าอยู่</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <?php
                    // ดึงข้อมูลทั้งหมดจากตาราง room
                    $sql = "SELECT * FROM room";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $count = 0;
                        // วนลูปแสดงการ์ดห้องแต่ละห้อง
                        while ($row = $result->fetch_assoc()) {
                            // เริ่มแถวใหม่ทุกครั้งที่มีการเพิ่มครบ 3 คอลัมน์
                            if ($count % 3 == 0 && $count != 0) {
                                echo '</div><div class="row mt-4">'; // ปิดแถวก่อนหน้าและเปิดแถวใหม่
                            }
                    ?>

                            <div class="col-md-4">
                                <div class="card text-center mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">ห้อง <?= $row['number_room']; ?></h5> <!-- ใช้ฟิลด์ที่เหมาะสม -->
                                        <p class="card-text">สถานะ: <?= $row['status_room']; ?></p> <!-- ใช้ฟิลด์สถานะ -->
                                        <a href="detail_room?id_room=<?= $row['id_room']; ?>" class="btn btn-primary">จัดการข้อมูล</a>
                                    </div>
                                </div>
                            </div>
                    <?php
                            $count++;
                        }
                    } else {
                        echo "<p>ไม่มีข้อมูลห้องพัก</p>";
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