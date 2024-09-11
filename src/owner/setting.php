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
    $comment = $_POST['comment'];

    // ตรวจสอบว่าค่าที่ได้รับไม่ว่างเปล่า
    if (!empty($id_apartment) && !empty($name_apartment) && !empty($address) && !empty($w_bath_unit) && !empty($e_bath_unit) && !empty($comment)) {
        // สร้าง SQL สำหรับอัปเดตข้อมูล
        $sql = "UPDATE apartment_data 
                SET name_apartment = ?, address = ?, w_bath_unit = ?, e_bath_unit = ?, comment = ? 
                WHERE id_apartment = ?";

        // เตรียม statement
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // bind ค่า
            $stmt->bind_param("sssssi", $name_apartment, $address, $w_bath_unit, $e_bath_unit, $comment, $id_apartment);

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
                echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error;
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

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การตั้งค่า</title>
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
                <h1>การจัดการข้อมูลพื้นฐานห้องพัก</h1>
                <?php
                // ดึงข้อมูลทั้งหมดจากตาราง room
                $sql0 = "SELECT * FROM room";
                $result0 = $conn->query($sql0);

                // นับจำนวนห้อง
                $count = $result0->num_rows;

                $sql = "SELECT * FROM apartment_data";
                $result = $conn->query($sql);

                $row = $result->fetch_assoc();
                $id_owner = $row['id_owner'];
                // ข้อมูลผู้เช่า
                $sql1 = "SELECT * FROM owner WHERE id_owner = $id_owner";
                $result1 = $conn->query($sql1);

                $row1 = $result1->fetch_assoc();
                $full_name = $row1['first_name'] . ' ' . $row1['last_name'] . ' ( ' . $row1['tel'] . ' )';
                ?>

                <div class="container mt-4">
                    <div class="row">

                        <!-- ข้อมูลหอพัก -->
                        <div class="col-md-12 mb-3">
                            <div class="card text-black" style="background-color: rgba(40, 167, 69, 0.5);">
                                <div class="card-header"><strong>ข้อมูลหอพัก</strong></div>
                                <div class="card-body">
                                    <p class="card-text"> - ชื่อหอพัก : <?= $row['name_apartment']; ?></p>
                                    <p class="card-text"> - จำนวนห้องพัก : <?= $count ?></p>
                                    <p class="card-text"> - ที่อยู่หอพัก : <?= $row['address']; ?></p>
                                    <p class="card-text"> - ค่าน้ำ : 1 UNIT : <?= $row['w_bath_unit']; ?> บาท</p>
                                    <p class="card-text"> - ค่าไฟฟ้า : 1 UNIT : <?= $row['e_bath_unit']; ?> บาท</p>
                                    <p class="card-text"> - หมายเหตุ : <?= $row['comment']; ?></p>

                                    <!-- ปุ่มแก้ไขข้อมูล -->
                                    <center><button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal1">
                                            แก้ไขข้อมูล
                                        </button></center>
                                </div>
                            </div>
                        </div>

                        <!-- Bootstrap Modal สำหรับแก้ไขข้อมูล -->
                        <div class="modal fade" id="editModal1" tabindex="-1" aria-labelledby="editModalLabel1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel1">แก้ไขข้อมูลหอพัก</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="?update=TRUE" method="POST">
                                            <input type="hidden" name="id_apartment" value="<?= $row['id_apartment']; ?>">
                                            <div class="mb-3">
                                                <label for="name_apartment" class="form-label">ชื่อหอพัก</label>
                                                <input type="text" class="form-control" id="name_apartment" name="name_apartment" value="<?= $row['name_apartment']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="address" class="form-label">ที่อยู่หอพัก</label>
                                                <input type="text" class="form-control" id="address" name="address" value="<?= $row['address']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="w_bath_unit" class="form-label">ค่าน้ำ (บาทต่อหน่วย)</label>
                                                <input type="number" class="form-control" id="w_bath_unit" name="w_bath_unit" value="<?= $row['w_bath_unit']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="e_bath_unit" class="form-label">ค่าไฟฟ้า (บาทต่อหน่วย)</label>
                                                <input type="number" class="form-control" id="e_bath_unit" name="e_bath_unit" value="<?= $row['e_bath_unit']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="comment" class="form-label">หมายเหตุ</label>
                                                <textarea class="form-control" id="comment" name="comment" required><?= $row['comment']; ?></textarea>
                                            </div>
                                            <center><button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button></center>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <!-- ข้อมูลเจ้าของหอพัก -->
                        <div class="col-md-12 mb-3">
                            <div class="card text-black" style="background-color: rgba(173, 216, 230, 0.5);">
                                <div class="card-header"><strong>ข้อมูลเจ้าของหอพัก</strong></div>
                                <form action="" method="POST">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-3">
                                                    <label class="form-label" for="first_name">ชื่อ<br></label>
                                                    <input type="text" name="txt_firstname" class="form-control" value="<?= $row1['first_name'] ?>">
                                                    <input type="hidden" name="id_owner" class="form-control" value="<?= $row1['id_owner'] ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Email<br></label>
                                                    <input type="text" name="txt_email" class="form-control" value="<?= $row1['email'] ?>">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="mb-3">
                                                    <label class="form-label" for="lastname">นามสกุล</label>
                                                    <input type="text" name="txt_lastname" class="form-control" value="<?= $row1['last_name'] ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="tel">เบอร์โทรศัพท์<br></label>
                                                    <input type="text" name="txt_tel" class="form-control" maxlength="10" size="10" value="<?= $row1['tel'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ปุ่มบันทึกข้อมูล -->
                                        <center><button type="submit" class="btn btn-warning" name="submit1">บันทึกข้อมูล
                                            </button></center>
                                    </div>
                                </form>
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