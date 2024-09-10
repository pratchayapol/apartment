<?php
session_start();
include "../config_db.php";
// ปิดการแสดงข้อผิดพลาด
// error_reporting(0);
// ini_set('display_errors', 0);


if (isset($_SESSION['id_room'])) {
    $id_room = $_SESSION['id_room'];

    // ดึงข้อมูลทั้งหมดจากตาราง room
    $sql = "SELECT * FROM room WHERE id_room = $id_room";
    $result = $conn->query($sql);
}

if (isset($_GET['upload_payment']) && $_GET['upload_payment'] == 'true') {
    // ตรวจสอบการอัพโหลดไฟล์
    if (isset($_FILES['slip']) && $_FILES['slip']['error'] == 0) {
        $id_rental = $_POST['id_rental'];

        // ตั้งค่าที่เก็บไฟล์
        $targetDir = "../img/";
        $fileName = basename($_FILES["slip"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // ตรวจสอบประเภทของไฟล์ (อนุญาตเฉพาะ .jpg, .jpeg, .png, .pdf)
        $allowedTypes = array('jpg', 'jpeg', 'png', 'pdf');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // อัพโหลดไฟล์ไปยังโฟลเดอร์ที่ตั้งไว้
            if (move_uploaded_file($_FILES["slip"]["tmp_name"], $targetFilePath)) {

                // อัพเดทชื่อไฟล์ในตาราง rental
                $sql = "UPDATE rental SET slip = '$fileName', step = 1 WHERE id_rental = $id_rental";
                if (mysqli_query($conn, $sql)) {
?>
                    <script>
                        setTimeout(function() {
                            Swal.fire({
                                title: '<div class="t1">บันทึกสลิปสำเร็จ</div>',
                                icon: 'success',
                                confirmButtonText: '<div class="text t1">ตกลง</div>',
                                allowOutsideClick: false, // Disable clicking outside popup to close
                                allowEscapeKey: false, // Disable ESC key to close
                                allowEnterKey: false // Disable Enter key to close
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "index";

                                }
                            });
                        }, 100); // Adjust timeout duration if needed
                    </script>
<?php
                } else {
                    echo "เกิดข้อผิดพลาดในการอัพเดท: " . mysqli_error($conn);
                }

                // ปิดการเชื่อมต่อ

            } else {
                echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์";
            }
        } else {
            echo "ประเภทไฟล์ไม่รองรับ (อนุญาตเฉพาะไฟล์ .jpg, .jpeg, .png, .pdf เท่านั้น)";
        }
    } else {
        echo "กรุณาเลือกไฟล์ที่ต้องการอัพโหลด";
    }
}
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



    <?php
    // ข้อมูลผู้เช่า
    $sql1 = "SELECT * FROM tenant WHERE id_room = $id_room";
    $result1 = $conn->query($sql1);

    $row1 = $result1->fetch_assoc();
    if ($row1 && isset($row1['first_name']) && isset($row1['last_name'])) {
        $full_name = $row1['first_name'] . ' ' . $row1['last_name'];

    ?>

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-12">
                    <h1>หน้าหลัก</h1>
                    <!-- <p>ในหน้านี้คุณสามารถเลือกห้องเพื่อแก้ไขข้อมูล</p> -->

                    <div class="row">
                        <?php
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $count = 0;

                            //ข้อมูลพื้นฐาน
                            $sql0 = "SELECT * FROM apartment_data";
                            $result0 = $conn->query($sql0);
                            $row0 = $result0->fetch_assoc();



                            $number_room = $row['number_room'];
                            $charge_room = $row['charge_room'];




                            // ข้อมูลมิเตอร์สำหรับรายการสุดท้ายและรองสุดท้าย
                            $sql2 = "SELECT * FROM meter WHERE id_room = $id_room ORDER BY id_meter  DESC LIMIT 2";
                            $result2 = $conn->query($sql2);

                            // ตั้งค่าเริ่มต้นให้ตัวแปร
                            //น้ำ
                            $number_water_meter_second_last = null;
                            $number_water_meter_last = null;

                            //ไฟฟ้า
                            $number_electricity_meter_last = null;
                            $number_electricity_meter_second_last = null;

                            if ($result2->num_rows == 2) {
                                // ดึงข้อมูลรองสุดท้ายและสุดท้าย
                                $row2_last = $result2->fetch_assoc(); // ข้อมูลสุดท้าย
                                $row2_second_last = $result2->fetch_assoc(); // ข้อมูลรองสุดท้าย
                                $number_water_meter_last = $row2_last['number_water_meter'];
                                $number_water_meter_second_last = $row2_second_last['number_water_meter'];

                                $number_electricity_meter_last = $row2_last['number_electricity_meter'];
                                $number_electricity_meter_second_last = $row2_second_last['number_electricity_meter'];
                            } elseif ($result2->num_rows == 1) {
                                // ถ้ามีแค่ข้อมูลสุดท้ายเพียงรายการเดียว
                                $row2_last = $result2->fetch_assoc();
                                $number_water_meter_last = $row2_last['number_water_meter'];
                                $number_water_meter_second_last = "( ไม่มีหมายเลขมิเตอร์น้ำก่อนหน้า )"; // ไม่มีข้อมูลรองสุดท้าย

                                $number_electricity_meter_last = $row2_last['number_electricity_meter'];
                                $number_electricity_meter_second_last = null; // ไม่มีข้อมูลรองสุดท้าย
                            }

                            // ตรวจสอบว่ามีข้อมูลทั้งสองหน่วย น้ำ
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


                            // ตรวจสอบว่ามีข้อมูลทั้งสองหน่วย ไฟฟ้า
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
                                $difference1 = "0";
                            }


                            // คำนวณค่าน้ำและค่าไฟฟ้า
                            $water_cost = $difference * $row0['w_bath_unit']; // ค่าน้ำ
                            $electricity_cost = $difference1 * $row0['e_bath_unit']; // ค่าไฟฟ้า

                            // ผลรวมทั้งหมด
                            $total_cost = $charge_room + $water_cost + $electricity_cost;

                            if (($water_cost > 0) && ($electricity_cost > 0)) {
                                $button_send = "true";
                            }
                        ?>
                            <div class="container mt-4">
                                <div class="row">
                                    <!-- ข้อมูลห้อง -->
                                    <div class="col-md-12 mb-3">
                                        <div class="card text-white bg-success">
                                            <div class="card-header">ข้อมูลห้อง <?= $row['number_room']; ?></div>
                                            <div class="card-body">
                                                <p class="card-text"> - ผู้เช่าห้อง : <?= $full_name . ' Tel : ' . $row1['tel'] . ' mail : ' . $row1['email'] ?> </p>
                                                <p class="card-text"> - เลขมิเตอร์น้ำก่อนหน้า : <?= $number_water_meter_second_last ?> เลขมิเตอร์น้ำล่าสุด : <?= $number_water_meter_last ?> <?= $def ?></p>
                                                <p class="card-text"> - เลขมิเตอร์ไฟฟ้าก่อนหน้า : <?= $number_electricity_meter_second_last ?> เลขมิเตอร์ไฟฟ้าล่าสุด : <?= $number_electricity_meter_last ?> <?= $def1 ?></p>
                                                <p class="card-text"> - ค่าห้อง : <?= number_format($charge_room) . " + ค่าน้ำ ( " . $difference ?> X <?= $row0['w_bath_unit'] . ' ) + ค่าไฟฟ้า ( ' . $difference1 . " X " . $row0['e_bath_unit'] . " ) รวมค่าห้องทั้งสิ้น " . number_format($total_cost) . " บาท" ?></p>

                                                <!-- Section for PDF icons -->
                                                <div class="pdf-icons mt-3">
                                                    <p class="card-text">- ใบแจ้งหนี้ PDF: </p>
                                                    <?php
                                                    // Get current month and year in the format 'F Y'
                                                    $month1 = date('F Y');

                                                    // Query to get the most recent record from the meter table
                                                    $query5 = "SELECT * FROM meter WHERE id_room = $id_room ORDER BY id_meter DESC";
                                                    $result5 = $conn->query($query5);
                                                    $row5 = $result5->fetch_assoc();

                                                    // Convert the database timestamp to 'F Y' format
                                                    $meterTimestamp = date('F Y', strtotime($row5['meter_timestam']));
                                                    function thaiDate($date)
                                                    {
                                                        $thai_month_arr = array(
                                                            "มกราคม",
                                                            "กุมภาพันธ์",
                                                            "มีนาคม",
                                                            "เมษายน",
                                                            "พฤษภาคม",
                                                            "มิถุนายน",
                                                            "กรกฎาคม",
                                                            "สิงหาคม",
                                                            "กันยายน",
                                                            "ตุลาคม",
                                                            "พฤศจิกายน",
                                                            "ธันวาคม"
                                                        );

                                                        // แยกส่วนของเดือนและปีจากวันที่ที่ได้รับมา
                                                        $month = date('n', strtotime($date)); // เอาเฉพาะตัวเลขเดือน (1-12)
                                                        $year = date('Y', strtotime($date)) + 543; // เปลี่ยนปี ค.ศ. เป็น พ.ศ.

                                                        // คืนค่าเดือนภาษาไทยและปี พ.ศ.
                                                        return $thai_month_arr[$month - 1] . ' ' . $year;
                                                    }
                                                    for ($i = 0; $i < 1; $i++) {
                                                        // สร้างชื่อเดือนย้อนหลังจากเดือนปัจจุบัน
                                                        $month = date('F Y', strtotime("-$i month"));



                                                        // Display PDF icons with links
                                                        if ($month1 === $meterTimestamp) {
                                                    ?>
                                                            <a href='../PDF/pdf?email=<?= $row1['email'] ?>&id_room=<?= $row['number_room'] ?>&f_name=<?= $full_name ?>&wc=<?= $water_cost ?>&we=<?= $electricity_cost ?>&cr=<?= $charge_room ?>&total=<?= $total_cost ?>&my=<?= thaiDate($month) ?>&name_ap=<?= $row0['name_apartment'] ?>&wc1=<?= $number_water_meter_second_last ?>&wc2=<?= $number_water_meter_last ?>&wc3=<?= $difference ?>&we1=<?= $number_electricity_meter_second_last ?>&we2=<?= $number_electricity_meter_last ?>&we3=<?= $difference1 ?>&wc4=<?= $row0['w_bath_unit'] ?>&we4=<?= $row0['e_bath_unit'] ?>&comment=<?= $row0['comment']; ?>' target='_blank' class='pdf-icon'>
                                                                <i class='fas fa-file-pdf'></i> <?= thaiDate($month) ?>
                                                            </a>
                                                        <?php

                                                        } else {
                                                        ?>
                                                            <a href='' class='pdf-icon'>
                                                                <i class='fas fa-file-pdf'></i> ไม่สามารถ REPORT ได้
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>


                                                    <?php
                                                    }
                                                    ?>


                                                    <!-- <p class="card-text">- ส่ง mail ใบแจ้งหนี้เดือนปัจจุบัน: </p> -->
                                                    <?php


                                                    // Compare $month1 with the formatted timestamp
                                                    if ($month1 === $meterTimestamp) {
                                                    ?>
                                                        <!-- <a href='../send_email?email=<?= $row1['email'] ?>&id_room=<?= $row['number_room'] ?>&f_name=<?= $full_name ?>&wc=<?= $water_cost ?>&we=<?= $electricity_cost ?>&cr=<?= $charge_room ?>&total=<?= $total_cost ?>' target='_blank' class='mail-icon'>
                                                            <i class='fas fa-envelope'></i> <?= thaiDate($month1); ?>
                                                        </a> -->
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <!-- <a href='' class='mail-icon'>
                                                            <i class='fas fa-envelope'></i> ไม่สามารถส่งอีเมลได้
                                                        </a> -->
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- ข้อมูลค่าเช่าย้อนหลัง -->
                                    <div class="col-md-12 mb-3">
                                        <div class="card text-white bg-primary">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <span>ค่าเช่าย้อนหลัง</span>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>เดือน ปี</th>
                                                            <th>ค่าห้อง</th>
                                                            <th>ค่าน้ำ</th>
                                                            <th>ค่าไฟ</th>
                                                            <th>สุทธิ</th>
                                                            <th>สถานะ</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php


                                                        $sql = "SELECT r.id_rental, r.rental_timestam, r.water_bill, r.electricity_bill, r.net, r.step, rm.charge_room 
                            FROM rental r 
                            JOIN room rm ON r.id_room = rm.id_room 
                            WHERE r.id_room = $id_room";

                                                        $result = mysqli_query($conn, $sql);

                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $rentalDate = date('F Y', strtotime($row['rental_timestam'])); // แสดงเดือนและปี
                                                                $chargeRoom = $row['charge_room'];
                                                                $waterBill = $row['water_bill'];
                                                                $electricityBill = $row['electricity_bill'];
                                                                $net = $row['net'];
                                                                $step = '';
                                                                switch ($row['step']) {
                                                                    case 0:
                                                                        $step = 'รอชำระ';
                                                                        break;
                                                                    case 1:
                                                                        $step = 'รอตรวจสอบ';
                                                                        break;
                                                                    case 2:
                                                                        $step = 'ชำระแล้ว';
                                                                        break;
                                                                    default:
                                                                        $step = 'สถานะไม่รู้จัก'; // Default message for unknown status
                                                                        break;
                                                                }
                                                                $idRental = $row['id_rental'];
                                                        ?>
                                                                <tr>
                                                                    <td><?= $rentalDate; ?></td>
                                                                    <td><?= number_format($chargeRoom, 2); ?></td>
                                                                    <td><?= number_format($waterBill, 2); ?></td>
                                                                    <td><?= number_format($electricityBill, 2); ?></td>
                                                                    <td><?= number_format($net, 2); ?></td>

                                                                    <td>
                                                                        <?php if ($step == "รอชำระ") { ?>
                                                                            <!-- ปุ่ม popup อัพโหลดสลิป -->
                                                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#paymentModal<?= $idRental; ?>">
                                                                                ชำระเงิน
                                                                            </button>

                                                                            <!-- Modal ฟอร์มอัพโหลดสลิป -->
                                                                            <div class="modal fade" id="paymentModal<?= $idRental; ?>" tabindex="-1" aria-labelledby="paymentModalLabel<?= $idRental; ?>" aria-hidden="true">
                                                                                <div class="modal-dialog">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="paymentModalLabel<?= $idRental; ?>">อัพโหลดสลิปชำระเงิน</h5>
                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <form action="?upload_payment=true" method="POST" enctype="multipart/form-data">
                                                                                            <div class="modal-body">
                                                                                                <input type="hidden" name="id_rental" value="<?= $idRental; ?>">
                                                                                                <div class="mb-3">
                                                                                                    <label for="slip" class="form-label">เลือกไฟล์สลิป</label>
                                                                                                    <input type="file" class="form-control" id="slip" name="slip" required>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                                                                <button type="submit" class="btn btn-primary">อัพโหลด</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else if ($step == "รอตรวจสอบ") { ?>
                                                                            <button class="btn btn-warning" disabled>รอตรวจสอบ</button>
                                                                        <?php } else if ($step == "ชำระแล้ว") { ?>
                                                                            <button class="btn btn-success" disabled>ชำระแล้ว</button>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>";
                                                        }

                                                        // ปิดการเชื่อมต่อฐานข้อมูล

                                                        ?>
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
            <script>
                function limitInput(input) {
                    if (input.value.length > 4) {
                        input.value = input.value.slice(0, 4);
                    }
                }
            </script>

        <?php
    } else {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">ไม่สามารถจัดการได้เนื่องจากยังเป็นห้องว่าง</div>',
                        icon: 'error',
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
        <?php } ?>
        <!-- Bootstrap 5 JS (Optional, for features like modals or tooltips) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>