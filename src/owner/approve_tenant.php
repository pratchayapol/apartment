<?php
session_start();
include "../config_db.php";
if (isset($_GET['update'])) {
    // รับค่าจากฟอร์ม
    $id_tenant = $_GET['update'];

    // ดึงห้องที่ยังไม่ถูกเช่า
    $query = "
        SELECT r.id_room, r.number_room 
        FROM room r 
        LEFT JOIN tenant t ON r.id_room = t.id_room 
        WHERE t.id_room IS NULL
    ";
    $result = $conn->query($query);

    $rooms = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
    }


    // ส่งข้อมูลห้องที่ยังไม่ถูกเช่าเป็น JSON
    $roomsJson = json_encode($rooms);

    if (isset($_GET['selected_room'])) {
        $selected_room = $_GET['selected_room'];

        // สร้างคำสั่ง SQL สำหรับการอัพเดต
        $updateQuery = "
            UPDATE tenant 
            SET id_room = ? 
            WHERE id_tenant = ?
        ";

        // เตรียมคำสั่ง SQL
        if ($stmt = $conn->prepare($updateQuery)) {
            // ผูกค่าพารามิเตอร์
            $stmt->bind_param("ii", $selected_room, $id_tenant);

            // รันคำสั่ง SQL
            if ($stmt->execute()) {
?>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: '<div class="t1">อนุมัติสำเร็จ</div>',
                            icon: 'success',
                            confirmButtonText: '<div class="text t1">ตกลง</div>',
                            allowOutsideClick: false, // Disable clicking outside popup to close
                            allowEscapeKey: false, // Disable ESC key to close
                            allowEnterKey: false // Disable Enter key to close
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "approve_tenant";

                            }
                        });
                    }, 100); // Adjust timeout duration if needed
                </script>
<?php
            } else {
                echo "Error updating tenant: " . $stmt->error;
            }

            // ปิดคำสั่ง SQL
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
} else {
    $roomsJson = '[]'; // หากไม่ได้รับพารามิเตอร์ update ให้ส่ง JSON ว่าง
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_GET['update'])): ?>
            const rooms = <?php echo $roomsJson; ?>;
            const roomOptions = rooms.reduce((options, room) => {
                options[room.id_room] = room.number_room;
                return options;
            }, {});

            Swal.fire({
                title: 'Select an Available Room',
                input: 'select',
                inputOptions: roomOptions,
                inputPlaceholder: 'Select a room',
                showCancelButton: true,
                confirmButtonText: 'Select',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedRoomId = result.value;
                    console.log('Selected room ID:', selectedRoomId);
                    // เปลี่ยน URL และส่งค่าที่เลือกไปยังหน้าถัดไป
                    window.location.href = '?update=<?php echo $_GET['update']; ?>&selected_room=' + selectedRoomId;
                }
            });
        <?php endif; ?>
    });
</script>
<?php



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

                        <!-- ข้อมูลผู้เช่า  -->
                        <div class="col-md-12 mb-3">
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
                                                <th>อนุมัติ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // ดึงข้อมูลจากฐานข้อมูล
                                            $sql3 = "SELECT * FROM `tenant` WHERE `id_room` = 0";
                                            $result3 = $conn->query($sql3);

                                            if ($result3->num_rows > 0) {
                                                // ข้อมูลถูกต้อง
                                                while ($row3 = $result3->fetch_assoc()) {
                                                    $tenant_id = $row3["id_tenant"];
                                                    $full_name = $row3["first_name"] . ' ' . $row3["last_name"];

                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($full_name) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row3["tel"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row3["email"]) . "</td>";
                                                    echo "<td>รออนุมัติ</td>";
                                            ?>
                                                    <td><a href='?update=<?= $tenant_id ?>' class='btn btn-success'>อนุมัติ</a></td>
                                            <?php
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
                        </div>


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