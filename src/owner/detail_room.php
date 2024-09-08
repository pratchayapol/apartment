<?php
session_start();
include "../config_db.php";
error_reporting(0);
ini_set('display_errors', 0);

if (isset($_GET['id_room'])) {
    $id_room = $_GET['id_room'];
}

if (isset($_GET['add_w_id'])) {

    $number_room10 = $_GET['add_w_id'];
    // รับข้อมูลจาก $_POST
    $meterNumber = isset($_POST['meterNumber']) ? $conn->real_escape_string($_POST['meterNumber']) : '';

    // สร้างคำสั่ง SQL สำหรับการแทรกข้อมูล
    $sql6 = "INSERT INTO `water_meter` (`id_water_meter`, `number_room`, `number_water_meter`, `save_water_meter`) VALUES (NULL, '$number_room10', '$meterNumber', NOW())";

    // ดำเนินการคำสั่ง SQL
    if ($conn->query($sql6) === TRUE) {
?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">บันทึกค่ามิเตอร์น้ำสำเร็จ</div>',
                    icon: 'success',
                    confirmButtonText: '<div class="text t1">ตกลง</div>',
                    allowOutsideClick: false, // Disable clicking outside popup to close
                    allowEscapeKey: false, // Disable ESC key to close
                    allowEnterKey: false // Disable Enter key to close
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "?id_room=<?= $id_room ?>";
                    }
                });
            }, 100); // Adjust timeout duration if needed
        </script>
    <?php
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}

if (isset($_GET['add_e_id'])) {

    $number_room10 = $_GET['add_e_id'];
    // รับข้อมูลจาก $_POST
    $meterNumber = isset($_POST['meterNumber']) ? $conn->real_escape_string($_POST['meterNumber']) : '';

    // สร้างคำสั่ง SQL สำหรับการแทรกข้อมูล
    $sql7 = "INSERT INTO `electricity_meter` (`id_electricity_meter`, `number_room`, `number_electricity_meter`, `save_electricity_meter`) VALUES (NULL, '$number_room10', '$meterNumber', NOW())";

    // ดำเนินการคำสั่ง SQL
    if ($conn->query($sql7) === TRUE) {
    ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">บันทึกค่ามิเตอร์ไฟฟ้าสำเร็จ</div>',
                    icon: 'success',
                    confirmButtonText: '<div class="text t1">ตกลง</div>',
                    allowOutsideClick: false, // Disable clicking outside popup to close
                    allowEscapeKey: false, // Disable ESC key to close
                    allowEnterKey: false // Disable Enter key to close
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "?id_room=<?= $id_room ?>";
                    }
                });
            }, 100); // Adjust timeout duration if needed
        </script>
    <?php
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}


if (isset($_POST['edit_w_id'])) {
    $idRoom = $_POST['id_room'];
    $editId = $_POST['edit_w_id'];
    $numberWaterMeter = $_POST['number_water_meter'];

    // ทำการอัพเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE water_meter SET number_water_meter = ?, save_water_meter = NOW() WHERE id_water_meter = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $numberWaterMeter, $editId);

    if ($stmt->execute()) {
    ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">แก้ไขมิเตอร์น้ำสำเร็จ</div>',
                    icon: 'success',
                    confirmButtonText: '<div class="text t1">ตกลง</div>',
                    allowOutsideClick: false, // Disable clicking outside popup to close
                    allowEscapeKey: false, // Disable ESC key to close
                    allowEnterKey: false // Disable Enter key to close
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "?id_room=<?= $id_room ?>";
                    }
                });
            }, 100); // Adjust timeout duration if needed
        </script>
    <?php
    } else {
        $response['error'] = 'Execute failed: ' . $stmt->error;
    }

    // ปิดคำสั่ง
    $stmt->close();
}

if (isset($_POST['edit_e_id'])) {
    $idRoom = $_POST['id_room'];
    $editId1 = $_POST['edit_e_id'];
    $numberelectricityMeter = $_POST['number_electricity_meter'];

    // ทำการอัพเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE electricity_meter SET number_electricity_meter = ?, save_electricity_meter = NOW() WHERE id_electricity_meter = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $numberelectricityMeter, $editId1);

    if ($stmt->execute()) {
    ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">แก้ไขมิเตอร์ไฟฟ้าสำเร็จ</div>',
                    icon: 'success',
                    confirmButtonText: '<div class="text t1">ตกลง</div>',
                    allowOutsideClick: false, // Disable clicking outside popup to close
                    allowEscapeKey: false, // Disable ESC key to close
                    allowEnterKey: false // Disable Enter key to close
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "?id_room=<?= $id_room ?>";
                    }
                });
            }, 100); // Adjust timeout duration if needed
        </script>
    <?php
    } else {
        $response['error'] = 'Execute failed: ' . $stmt->error;
    }

    // ปิดคำสั่ง
    $stmt->close();
}


// ลบมิเตอร์น้ำ
if (isset($_GET['del_w_id'])) {
    $id_water_meter = $_GET['del_w_id'];



    // เตรียมคำสั่ง SQL
    $sql = "DELETE FROM water_meter WHERE id_water_meter = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_water_meter);

    if ($stmt->execute()) {
    ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">ลบมิเตอร์น้ำสำเร็จ</div>',
                    icon: 'success',
                    confirmButtonText: '<div class="text t1">ตกลง</div>',
                    allowOutsideClick: false, // Disable clicking outside popup to close
                    allowEscapeKey: false, // Disable ESC key to close
                    allowEnterKey: false // Disable Enter key to close
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "?id_room=<?= $id_room ?>";
                    }
                });
            }, 100); // Adjust timeout duration if needed
        </script>
<?php
    } else {
        echo "Execute failed: " . $stmt->error;
    }

    // ปิดคำสั่ง
    $stmt->close();
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
                        $number_room = $row['number_room'];
                        // ข้อมูลผู้เช่า
                        $sql1 = "SELECT * FROM tenant WHERE id_tenant = $id_tenant";
                        $result1 = $conn->query($sql1);

                        $row1 = $result1->fetch_assoc();
                        $full_name = $row1['first_name'] . '  ' . $row1['last_name'] . ' ( ' . $row1['tel'] . ' )';



                        // ข้อมูลมิเตอร์น้ำสำหรับรายการสุดท้ายและรองสุดท้าย
                        $sql2 = "SELECT * FROM water_meter WHERE number_room = $number_room ORDER BY id_water_meter  DESC LIMIT 2";
                        $result2 = $conn->query($sql2);

                        // ตั้งค่าเริ่มต้นให้ตัวแปร
                        $number_water_meter_second_last = null;
                        $number_water_meter_last = null;

                        if ($result2->num_rows == 2) {
                            // ดึงข้อมูลรองสุดท้ายและสุดท้าย
                            $row2_last = $result2->fetch_assoc(); // ข้อมูลสุดท้าย
                            $row2_second_last = $result2->fetch_assoc(); // ข้อมูลรองสุดท้าย
                            $number_water_meter_last = $row2_last['number_water_meter'];
                            $number_water_meter_second_last = $row2_second_last['number_water_meter'];
                        } elseif ($result2->num_rows == 1) {
                            // ถ้ามีแค่ข้อมูลสุดท้ายเพียงรายการเดียว
                            $row2_last = $result2->fetch_assoc();
                            $number_water_meter_last = $row2_last['number_water_meter'];
                            $number_water_meter_second_last = "( ไม่มีหมายเลขมิเตอร์น้ำก่อนหน้า )"; // ไม่มีข้อมูลรองสุดท้าย
                        }

                        // ตรวจสอบว่ามีข้อมูลทั้งสองหน่วย
                        if (!is_null($number_water_meter_last) && !is_null($number_water_meter_second_last)) {
                            // แปลงเป็น float เพื่อให้แน่ใจว่าเป็นตัวเลข
                            $number_water_meter_last = (float)$number_water_meter_last;
                            $number_water_meter_second_last = (float)$number_water_meter_second_last;

                            // ตรวจสอบว่ามิเตอร์น้ำรีเซ็ตหรือไม่
                            if ($number_water_meter_last < $number_water_meter_second_last) {
                                // สมมติว่าค่ามิเตอร์น้ำเต็มรอบคือ 10000
                                $difference = ($number_water_meter_last + 10000) - $number_water_meter_second_last;
                            } else {
                                // กรณีปกติ
                                $difference = $number_water_meter_last - $number_water_meter_second_last;
                            }

                            $def = "น้ำที่ใช้ไปในเดือนนี้: " . $difference . " หน่วย";
                        } else {
                            $def = "ไม่สามารถคำนวณค่าต่างได้ เนื่องจากข้อมูลไม่ครบถ้วน";
                        }

                        // ข้อมูลมิเตอร์ไฟฟ้าสำหรับรายการสุดท้ายและรองสุดท้าย
                        $sql3 = "SELECT * FROM electricity_meter WHERE number_room = $number_room ORDER BY id_electricity_meter DESC LIMIT 2";
                        $result3 = $conn->query($sql3);

                        // ตั้งค่าเริ่มต้นให้ตัวแปร
                        $number_electricity_meter_last = null;
                        $number_electricity_meter_second_last = null;

                        if ($result3->num_rows == 2) {
                            // ดึงข้อมูลรองสุดท้ายและสุดท้าย
                            $row3_last = $result3->fetch_assoc(); // ข้อมูลสุดท้าย
                            $row3_second_last = $result3->fetch_assoc(); // ข้อมูลรองสุดท้าย
                            $number_electricity_meter_last = $row3_last['number_electricity_meter'];
                            $number_electricity_meter_second_last = $row3_second_last['number_electricity_meter'];
                        } elseif ($result3->num_rows == 1) {
                            // ถ้ามีแค่ข้อมูลสุดท้ายเพียงรายการเดียว
                            $row3_last = $result3->fetch_assoc();
                            $number_electricity_meter_last = $row3_last['number_electricity_meter'];
                            $number_electricity_meter_second_last = null; // ไม่มีข้อมูลรองสุดท้าย
                        }

                        // ตรวจสอบว่ามีข้อมูลทั้งสองหน่วย
                        if (!is_null($number_electricity_meter_last) && !is_null($number_electricity_meter_second_last)) {
                            // แปลงเป็น float เพื่อให้แน่ใจว่าเป็นตัวเลข
                            $number_electricity_meter_last = (float)$number_electricity_meter_last;
                            $number_electricity_meter_second_last = (float)$number_electricity_meter_second_last;

                            // ตรวจสอบว่ามิเตอร์รีเซ็ตหรือไม่
                            if ($number_electricity_meter_last < $number_electricity_meter_second_last) {
                                // สมมติว่าค่ามิเตอร์เต็มรอบคือ 10000
                                $difference1 = ($number_electricity_meter_last + 10000) - $number_electricity_meter_second_last;
                            } else {
                                // กรณีปกติ
                                $difference1 = $number_electricity_meter_last - $number_electricity_meter_second_last;
                            }

                            $def1 = "ไฟฟ้าที่ใช้ไปในเดือนนี้: " . $difference1 . " หน่วย";
                        } else {
                            $def1 = "ไม่สามารถคำนวณค่าต่างได้ เนื่องจากข้อมูลไม่ครบถ้วน";
                        }

                    ?>
                        <div class="container mt-4">
                            <div class="row">
                                <!-- ข้อมูลห้อง -->
                                <div class="col-md-12 mb-3">
                                    <div class="card text-white bg-success">
                                        <div class="card-header">ข้อมูลห้อง <?= $row['number_room']; ?></div>
                                        <div class="card-body">
                                            <p class="card-text"> - ผู้เช่าห้อง : <?= $full_name ?></p>
                                            <p class="card-text"> - เลขมิเตอร์น้ำก่อนหน้า : <?= $number_water_meter_second_last ?> เลขมิเตอร์น้ำล่าสุด : <?= $number_water_meter_last ?> <?= $def ?></p>
                                            <p class="card-text"> - เลขมิเตอร์ไฟฟ้าก่อนหน้า : <?= $number_electricity_meter_second_last ?> เลขมิเตอร์ไฟฟ้าล่าสุด : <?= $number_electricity_meter_last ?> <?= $def1 ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- ข้อมูลมิเตอร์น้ำและไฟฟ้า -->
                                <div class="col-md-6 mb-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span>ประวัติเลขมิเตอร์น้ำ</span>
                                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addRoomModal">เพิ่มข้อมูล</button>

                                            <!-- Modal -->
                                            <div class="modal fade text-dark" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">กรอกเลขมิเตอร์น้ำ</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="?id_room=<?= $id_room ?>&add_w_id=<?= $row['number_room']; ?>" method="post">
                                                                <div class="form-group">
                                                                    <label for="meterNumber">เลขมิเตอร์น้ำ:</label>
                                                                    <input type="text" class="form-control" id="meterNumber" name="meterNumber" required>
                                                                </div><br>
                                                                <center><button type="submit" name="submit" class="btn btn-primary">บันทึกข้อมูล</button></center>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>เลขมิเตอร์น้ำ</th>
                                                        <th>วันที่บันทึก</th>
                                                        <th>การจัดการ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // ตัวอย่างการ query ข้อมูลมิเตอร์น้ำ
                                                    $query4 = "SELECT id_water_meter, number_water_meter, save_water_meter FROM water_meter WHERE number_room = $number_room ORDER BY id_water_meter DESC LIMIT 12";
                                                    $result4 = $conn->query($query4);
                                                    while ($row4 = $result4->fetch_assoc()) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $row4['number_water_meter']; ?></td>
                                                            <td><?= date('d-m-Y H:i:s', strtotime($row4['save_water_meter'])); ?></td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="openEditPopup('<?= $number_room ?>', '<?= $row4['id_water_meter'] ?>', '<?= $row4['number_water_meter'] ?>');" class="btn btn-warning btn-sm">แก้ไข</a>

                                                                <script>
                                                                    function openEditPopup(numberRoom, waterMeterId, number_water_meter) {
                                                                        Swal.fire({
                                                                            title: 'แก้ไขเลขมิเตอร์น้ำ',
                                                                            html: `
            <form action="?id_room=<?= $id_room ?>&edit_w_id=${waterMeterId}" method="post">
                <input type="hidden" name="id_room" value="${numberRoom}">
                <input type="hidden" name="edit_w_id" value="${waterMeterId}">
                <div class="form-group">
                    <label for="number_water_meter">เลขมิเตอร์น้ำ</label>
                    <input type="text" id="number_water_meter" name="number_water_meter" value="${number_water_meter}" class="form-control">
                </div><br>
                <div class="modal-footer">
                    <div class="d-flex justify-content-center w-100">
                        <button type="button" class="btn btn-secondary me-2" onclick="Swal.close()">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary" name="submit">บันทึก</button>
                    </div>
                </div>
            </form>
        `,
                                                                            focusConfirm: false,
                                                                            showConfirmButton: false,
                                                                            showCancelButton: false,
                                                                            showCloseButton: false,
                                                                        });

                                                                        // ใช้ CSS ของ Swal เพื่อจัดการการจัดวาง
                                                                        const footer = document.querySelector('.swal2-actions');
                                                                        if (footer) {
                                                                            footer.style.display = 'flex';
                                                                            footer.style.justifyContent = 'center';
                                                                        }
                                                                    }
                                                                </script>



                                                                <a href="?id_room=<?= $id_room ?>&del_w_id=<?= $row4['id_water_meter'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล?');">ลบ</a>
                                                            </td>
                                                            </td>
                                                        </tr>

                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card text-white bg-danger">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span>ประวัติเลขมิเตอร์ไฟฟ้า</span>
                                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addRoomModal1">เพิ่มข้อมูล</button>
                                            <!-- Modal -->
                                            <div class="modal fade text-dark" id="addRoomModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">กรอกเลขมิเตอร์ไฟฟ้า</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="?id_room=<?= $id_room ?>&add_e_id=<?= $row['number_room']; ?>" method="post">
                                                                <div class="form-group">
                                                                    <label for="meterNumber">เลขมิเตอร์ไฟฟ้า:</label>
                                                                    <input type="text" class="form-control" id="meterNumber" name="meterNumber" required>
                                                                </div><br>
                                                                <center><button type="submit" name="submit" class="btn btn-primary">บันทึกข้อมูล</button></center>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>เลขมิเตอร์ไฟฟ้า</th>
                                                        <th>วันที่บันทึก</th>
                                                        <th>การจัดการ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // ตัวอย่างการ query ข้อมูลมิเตอร์ไฟฟ้า
                                                    $query5 = "SELECT id_electricity_meter, number_electricity_meter, save_electricity_meter FROM electricity_meter WHERE number_room = $number_room ORDER BY id_electricity_meter DESC LIMIT 12";
                                                    $result5 = $conn->query($query5);
                                                    while ($row5 = $result5->fetch_assoc()) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $row5['number_electricity_meter']; ?></td>
                                                            <td><?= date('d-m-Y H:i:s', strtotime($row5['save_electricity_meter'])); ?></td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="openEditPopup1('<?= $number_room ?>', '<?= $row5['id_electricity_meter'] ?>', '<?= $row5['number_electricity_meter'] ?>');" class="btn btn-warning btn-sm">แก้ไข</a>
                                                                <script>
                                                                    function openEditPopup1(numberRoom, electricityMeterId, number_electricity_meter) {
                                                                        Swal.fire({
                                                                            title: 'แก้ไขเลขมิเตอร์ไฟฟ้า',
                                                                            html: `
            <form action="?id_room=<?= $id_room ?>&edit_e_id=${electricityMeterId}" method="post">
                <input type="hidden" name="id_room" value="${numberRoom}">
                <input type="hidden" name="edit_w_id" value="${electricityMeterId}">
                <div class="form-group">
                    <label for="number_electricity_meter">เลขมิเตอร์ไฟฟ้า</label>
                    <input type="text" id="number_electricity_meter" name="number_electricity_meter" value="${number_electricity_meter}" class="form-control">
                </div><br>
                <div class="modal-footer">
                    <div class="d-flex justify-content-center w-100">
                        <button type="button" class="btn btn-secondary me-2" onclick="Swal.close()">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary" name="submit">บันทึก</button>
                    </div>
                </div>
            </form>
        `,
                                                                            focusConfirm: false,
                                                                            showConfirmButton: false,
                                                                            showCancelButton: false,
                                                                            showCloseButton: false,
                                                                        });

                                                                        // ใช้ CSS ของ Swal เพื่อจัดการการจัดวาง
                                                                        const footer = document.querySelector('.swal2-actions');
                                                                        if (footer) {
                                                                            footer.style.display = 'flex';
                                                                            footer.style.justifyContent = 'center';
                                                                        }
                                                                    }
                                                                </script>


                                                                <a href="delete_electricity_meter.php?id=<?= $row5['id_electricity_meter']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล?');">ลบ</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS (Optional, for features like modals or tooltips) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>