<?php
session_start();
include "../config_db.php";
// ลบห้อง
if (isset($_GET['del_id_room'])) {
    $id_room = $_GET['del_id_room'];

    $sql = "SELECT * FROM room WHERE id_room = $id_room";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $number_room = $row['number_room'];
    // เริ่มทำ Transaction เพื่อให้การลบข้อมูลเป็น Atomic
    $conn->begin_transaction();

    try {
        // ลบข้อมูลจากตาราง water_meter โดยใช้ id_room
        $sql1 = "DELETE FROM water_meter WHERE number_room = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("i", $number_room);
        $stmt1->execute();

        // ลบข้อมูลจากตาราง electricity_meter โดยใช้ id_room
        $sql2 = "DELETE FROM electricity_meter WHERE number_room = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $number_room);
        $stmt2->execute();

        // ลบข้อมูลจากตาราง room
        $sql3 = "DELETE FROM room WHERE id_room = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $id_room);
        $stmt3->execute();

        // Commit transaction
        $conn->commit();
?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">ลบห้องสำเร็จ</div>',
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
            }, 100); // Adjust timeout duration if needed
        </script>
    <?php
    } catch (Exception $e) {
        // ถ้ามีข้อผิดพลาดเกิดขึ้น ยกเลิกการทำงานทั้งหมด (rollback)
        $conn->rollback();
        echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage();
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn->close();
}



// เพิ่มห้อง
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $room_code = $_POST['room_code'];
    $water_meter = $_POST['water_meter'];
    $electric_meter = $_POST['electric_meter'];
    $detail_room = $_POST['detail_room'];
    $charge_room = $_POST['charge_room'];



    // เริ่มทำ Transaction เพื่อให้การ insert ข้อมูลเป็น Atomic
    $conn->begin_transaction();

    try {
        // Insert ข้อมูลเข้าตาราง room
        $sql3 = "INSERT INTO room (number_room, detail_room, charge_room) VALUES (?, ?, ?)";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("ssi", $room_code, $detail_room, $charge_room);
        $stmt3->execute();

        // Get the last inserted ID from room
        $id_room = $conn->insert_id;

        // Insert ข้อมูลเข้าตาราง meter โดยใช้ id_room ที่ได้จากการ insert room
        $sql1 = "INSERT INTO meter (id_room, number_water_meter, number_electricity_meter, meter_timestam) VALUES (?, ?, ?, NOW())";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("iss", $id_room, $water_meter, $electric_meter);
        $stmt1->execute();

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
            }, 100); // Adjust timeout duration if needed
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
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>

                            </div>
                            <div class="modal-body">
                                <form id="addRoomForm" method="POST" action="">
                                    <div class="form-group">
                                        <label for="room_code">รหัสห้อง</label>
                                        <input type="text" class="form-control" id="room_code" name="room_code" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="water_meter">เลขมิเตอร์น้ำล่าสุด</label>
                                        <input type="number" class="form-control" id="water_meter" name="water_meter" min="0" max="9999" required oninput="limitInput(this)">
                                    </div>
                                    <div class="form-group">
                                        <label for="electric_meter">เลขมิเตอร์ไฟล่าสุด</label>
                                        <input type="number" class="form-control" id="electric_meter" name="electric_meter" min="0" max="9999" required oninput="limitInput(this)">
                                    </div>
                                    <div class="form-group">
                                        <label for="editDetailRoom">ประเภทห้อง</label>
                                        <select class="form-control" name="detail_room" required>
                                            <option value="ห้องพัดลม">ห้องพัดลม</option>
                                            <option value="ห้องแอร์">ห้องแอร์</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="editChargeRoom">ค่าเช่าเฉพาะห้อง</label>
                                        <input type="number" class="form-control" name="charge_room">
                                    </div>
                                    <br>
                                    <center>
                                        <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                                    </center>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <?php
                    // ดึงข้อมูลทั้งหมดจากตาราง room และตรวจสอบสถานะการเช่าจากตาราง tenant
                    $sql = "
    SELECT r.id_room, r.number_room, 
           IF(t.id_room IS NULL, 'ห้องว่าง', 'กำลังเช่าอยู่') AS status
    FROM room r
    LEFT JOIN tenant t ON r.id_room = t.id_room
";
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
                                        <h5 class="card-title">ห้อง <?= $row['number_room']; ?></h5>
                                        <p class="card-text">สถานะ: <?= htmlspecialchars($row['status']); ?></p>
                                        <a href="detail_room?id_room=<?= $row['id_room']; ?>" class="btn btn-primary">จัดการข้อมูล</a>
                                        <a href="#" class="btn btn-danger" onclick="confirmDelete(<?= $row['id_room']; ?>)">ลบห้อง</a>

                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                        <script>
                                            function confirmDelete(id_room) {
                                                Swal.fire({
                                                    title: 'คุณแน่ใจหรือไม่?',
                                                    text: "คุณต้องการลบห้องนี้หรือไม่",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'ใช่, ลบเลย!',
                                                    cancelButtonText: 'ยกเลิก'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        // ดำเนินการลบหลังจากผู้ใช้ยืนยัน
                                                        window.location.href = "?del_id_room=" + id_room;
                                                    }
                                                });
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $count++;
                        }
                    } else {
                        ?>
                        <div class="card text-white bg-danger mb-12">

                            <div class="card-body">
                                <center>
                                    <h1 class="card-title">!!! ไม่พบข้อมูลหอพัก !!!</h1>
                                </center>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div> <!-- ปิด row -->
            </div>
        </div>
    </div>
    <script>
        function limitInput(input) {
            if (input.value.length > 4) {
                input.value = input.value.slice(0, 4);
            }
        }
    </script>
    <!-- Bootstrap 5 JS (Optional, for features like modals or tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>