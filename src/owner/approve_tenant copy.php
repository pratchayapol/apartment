<?php
session_start();
include "../config_db.php";
if (isset($_GET['update']) && $_GET['update'] == "TRUE") {
    // รับค่าจากฟอร์ม
    $id_apartment = $_POST['id_apartment'];
    $name_apartment = $_POST['name_apartment'];
    $address = $_POST['address'];
    $w_bath_unit = $_POST['w_bath_unit'];
    $e_bath_unit = $_POST['e_bath_unit'];

    // ตรวจสอบว่าค่าที่ได้รับไม่ว่างเปล่า
    if (!empty($id_apartment) && !empty($name_apartment) && !empty($address) && !empty($w_bath_unit) && !empty($e_bath_unit)) {
        // สร้าง SQL สำหรับอัปเดตข้อมูล
        $sql = "UPDATE apartment_data 
                SET name_apartment = ?, address = ?, w_bath_unit = ?, e_bath_unit = ? 
                WHERE id_apartment = ?";

        // เตรียม statement
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // bind ค่า
            $stmt->bind_param("ssdsi", $name_apartment, $address, $w_bath_unit, $e_bath_unit, $id_apartment);

            // execute statement
            if ($stmt->execute()) {
?>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: '<div class="t1">แก้ไขข้อมูลหอพักสำเร็จ</div>',
                            icon: 'success',
                            confirmButtonText: '<div class="text t1">ตกลง</div>',
                            allowOutsideClick: false, // Disable clicking outside popup to close
                            allowEscapeKey: false, // Disable ESC key to close
                            allowEnterKey: false // Disable Enter key to close
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "setting";

                            }
                        });
                    }, 100); // Adjust timeout duration if needed
                </script>
        <?php
            } else {
                echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $conn->error;
            }

            // ปิด statement
            $stmt->close();
        } else {
            echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
        }
    } else {
        echo "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    }
}

// ตรวจสอบว่ามีการกดปุ่ม submit หรือไม่
if (isset($_POST['submit1'])) {
    // รับค่าจากฟอร์ม
    $id_owner = $_POST['id_owner'];
    $firstname = $_POST['txt_firstname'];
    $lastname = $_POST['txt_lastname'];
    $email = $_POST['txt_email'];
    $tel = $_POST['txt_tel'];

    // ดำเนินการอัปเดตข้อมูล เช่น การอัปเดตลงฐานข้อมูล
    $sql = "UPDATE owner SET first_name = '$firstname', last_name = '$lastname', email = '$email', tel = '$tel' WHERE id_owner = $id_owner";

    // รันคำสั่ง SQL ในฐานข้อมูล
    if (mysqli_query($conn, $sql)) {
        ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">แก้ไขข้อมูลเจ้าของหอพักสำเร็จ</div>',
                    icon: 'success',
                    confirmButtonText: '<div class="text t1">ตกลง</div>',
                    allowOutsideClick: false, // Disable clicking outside popup to close
                    allowEscapeKey: false, // Disable ESC key to close
                    allowEnterKey: false // Disable Enter key to close
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "setting";

                    }
                });
            }, 100); // Adjust timeout duration if needed
        </script>
    <?php
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_error($conn);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $number_room = $_POST['number_room'] ?? '';
    $status_room = $_POST['status_room'] ?? '';
    $detail_room = $_POST['detail_room'] ?? '';
    $charge_room = $_POST['charge_room'] ?? '';


    // ตรวจสอบว่าห้องมีอยู่หรือไม่
    $sql_check = "SELECT * FROM room WHERE number_room = '$number_room'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // ถ้าห้องมีอยู่แล้ว อัปเดตข้อมูลห้อง
        $sql_update = "UPDATE room 
                   SET status_room = '$status_room', 
                       detail_room = '$detail_room', 
                       charge_room = '$charge_room' 
                   WHERE number_room = '$number_room'";

        if ($conn->query($sql_update) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

if (isset($_GET['del_id'])) {
    $id_tenant = $_GET['del_id'];

    $sql_delete = "DELETE FROM tenant WHERE id_tenant = ?";

    // ใช้ prepared statement เพื่อความปลอดภัย
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id_tenant); // ผูกตัวแปร id_tenant กับ SQL

    // ตรวจสอบว่าการลบข้อมูลสำเร็จหรือไม่
    if ($stmt->execute()) {
    ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">ลบบัญชีสำเร็จ</div>',
                    icon: 'success',
                    confirmButtonText: '<div class="text t1">ตกลง</div>',
                    allowOutsideClick: false, // Disable clicking outside popup to close
                    allowEscapeKey: false, // Disable ESC key to close
                    allowEnterKey: false // Disable Enter key to close
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "setting";

                    }
                });
            }, 100); // Adjust timeout duration if needed
        </script>
<?php
    } else {
        echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อนุมัติผู้เช่า</title>
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
                <h1>อนุมัติผู้เช่า</h1>
               
                <div class="container mt-4">
                    <div class="row">

                        <?php

                        // Query ข้อมูลจากตาราง room
                        $sql2 = "SELECT number_room, detail_room, charge_room FROM room";
                        $result2 = $conn->query($sql2);
                        ?>

                        <!-- ข้อมูลห้องเช่า -->
                        <div class="col-md-12 mb-3">
                            <div class="card text-black" style="background-color: rgba(221, 160, 221, 0.5);">
                                <div class="card-header"><strong>ข้อมูลห้องเช่า</strong></div>
                                <div class="card-body">
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>เลขห้อง</th>
                                                <th>ประเภทห้อง</th>
                                                <th>ค่าเช่าเฉพาะห้อง</th>
                                                <th>แก้ไขข้อมูล</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result2->num_rows > 0): ?>
                                                <?php while ($row2 = $result2->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row2['number_room']); ?></td>
                                                        <td><?php echo htmlspecialchars($row2['detail_room']); ?></td>
                                                        <td><?php echo htmlspecialchars($row2['charge_room'] . ' บาท'); ?></td>
                                                        <td>
                                                            <center><a href="javascript:void(0);" onclick="openEditPopup('<?= $row2['number_room'] ?>', '<?= $row2['detail_room'] ?>', '<?= $row2['charge_room'] ?>');" class="btn btn-warning btn-sm">แก้ไข</a></center>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4">ไม่มีข้อมูลห้องเช่า</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                    <!-- Edit Room Modal -->
                                    <div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content text-dark">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editRoomModalLabel">แก้ไขข้อมูลห้องเช่า</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="editRoomForm">
                                                        <div class="form-group">
                                                            <label for="editNumberRoom">เลขห้อง</label>
                                                            <input type="text" class="form-control" id="editNumberRoom" name="number_room" readonly>
                                                        </div><br>
                                                        <div class="form-group">
                                                            <label for="editStatusRoom">สถานะห้อง</label>
                                                            <select class="form-control" id="editStatusRoom" name="room_status" required>
                                                                <option value="ว่าง">ว่าง</option>
                                                                <option value="ไม่ว่าง">ไม่ว่าง</option>
                                                                <option value="กำลังเช่าอยู่">กำลังเช่าอยู่</option>
                                                            </select>
                                                        </div><br>
                                                        <div class="form-group">
                                                            <label for="editDetailRoom">ประเภทห้อง</label>
                                                            <select class="form-control" id="editDetailRoom" name="detail_room" required>
                                                                <option value="ห้องพัดลม">ห้องพัดลม</option>
                                                                <option value="ห้องแอร์">ห้องแอร์</option>
                                                            </select>
                                                        </div><br>
                                                        <div class="form-group">
                                                            <label for="editChargeRoom">ค่าเช่าเฉพาะห้อง</label>
                                                            <input type="number" class="form-control" id="editChargeRoom" name="charge_room">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <center>
                                                        <button type="button" class="btn btn-primary" onclick="saveRoomData()">บันทึกการเปลี่ยนแปลง</button>
                                                    </center>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        function openEditPopup(number_room, detail_room, charge_room) {
                                            // กำหนดค่าเริ่มต้นให้กับฟอร์ม
                                            document.getElementById('editNumberRoom').value = number_room;
                                            document.getElementById('editDetailRoom').value = detail_room;
                                            document.getElementById('editChargeRoom').value = charge_room;

                                            // แสดง Modal Popup
                                            $('#editRoomModal').modal('show');
                                        }

                                        function saveRoomData() {
                                            // เก็บค่าจากฟอร์ม
                                            var number_room = document.getElementById('editNumberRoom').value;
                                            var detail_room = document.getElementById('editDetailRoom').value;
                                            var charge_room = document.getElementById('editChargeRoom').value;

                                            // ส่งข้อมูลไปยัง PHP ด้วย AJAX
                                            $.ajax({
                                                url: '', // ไฟล์ PHP ที่ใช้ในการบันทึกข้อมูล
                                                type: 'POST',
                                                data: {
                                                    number_room: number_room,
                                                    detail_room: detail_room,
                                                    charge_room: charge_room
                                                },
                                                success: function(response) {
                                                    // เมื่อบันทึกสำเร็จ ให้แสดง SweetAlert
                                                    setTimeout(function() {
                                                        Swal.fire({
                                                            title: '<div class="t1">แก้ไขข้อมูลห้องเช่าสำเร็จ</div>',
                                                            icon: 'success',
                                                            confirmButtonText: '<div class="text t1">ตกลง</div>',
                                                            allowOutsideClick: false, // Disable clicking outside popup to close
                                                            allowEscapeKey: false, // Disable ESC key to close
                                                            allowEnterKey: false // Disable Enter key to close
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                window.location.href = "setting";

                                                            }
                                                        });
                                                    }, 100); // Adjust timeout duration if needed

                                                    // ปิด Modal
                                                    $('#editRoomModal').modal('hide');
                                                },
                                                error: function(xhr, status, error) {
                                                    // เมื่อเกิดข้อผิดพลาด
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error!',
                                                        text: 'Something went wrong. Please try again.',
                                                    });
                                                }
                                            });
                                        }
                                    </script>

                                </div>
                            </div>
                        </div>

                        <!-- ข้อมูลผู้เช่า -->
                        <!-- <div class="col-md-12 mb-3">
                            <div class="card text-black" style="background-color: rgba(255, 255, 0, 0.5);">
                                <div class="card-header"><strong>ข้อมูลผู้เช่า</strong></div>
                                <div class="card-body">
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ชื่อ - สกุล ผู้เช่า</th>
                                                <th>เบอร์โทร</th>
                                                <th>อีเมล</th>
                                                <th>สถานะ</th>
                                                <th>ลบบัญชีผู้เช่า</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // ดึงข้อมูลจากฐานข้อมูล
                                            $sql3 = "SELECT * FROM tenant";
                                            $result3 = $conn->query($sql3);

                                            if ($result3->num_rows > 0) {
                                                // ข้อมูลถูกต้อง
                                                while ($row3 = $result3->fetch_assoc()) {
                                                    $tenant_id = $row3["id_tenant"];
                                                    $full_name = $row3["first_name"] . ' ' . $row3["last_name"];

                                                    // ตรวจสอบว่ามีการเช่าห้องอยู่หรือไม่
                                                    $sql_room = "SELECT * FROM room WHERE id_tenant = $tenant_id AND `status_room` LIKE 'กำลังเช่าอยู่'";
                                                    $result_room = $conn->query($sql_room);

                                                    if ($result_room->num_rows > 0) {
                                                        $status_message = "กำลังเช่าอยู่";
                                                    } else {
                                                        $status_message = "ไม่ได้เช่า";
                                                    }

                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($full_name) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row3["tel"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row3["email"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($status_message) . "</td>";
                                                    echo "<td><a href='?del_id=" . urlencode($tenant_id) . "' class='btn btn-danger' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบบัญชีผู้เช่า?\")'>ลบบัญชีผู้เช่า</a></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>ไม่มีข้อมูล</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> -->


                    </div>
                </div>


            </div>
        </div>
    </div>


    <!-- Bootstrap 5 JS (Optional, for features like modals or tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>