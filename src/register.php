<?php
session_start();
include "./config_db.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];


    // เข้ารหัสรหัสผ่านก่อนเก็บลงฐานข้อมูล
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($user_type == "tenant") {
        // SQL สำหรับเพิ่มข้อมูลผู้ใช้ลงฐานข้อมูล
        $sql = "INSERT INTO tenant (first_name, last_name, tel, email, password) VALUES (?, ?, ?, ?, ?)";

        // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $tel, $email, $hashed_password);

        if ($stmt->execute()) {
?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">สมัครใช้งานสำเร็จ</div>',
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
                }, 1000); // Adjust timeout duration if needed
            </script>
        <?php
        } else {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">เกิดข้อผิดพลาด</div>',
                        icon: 'error',
                        confirmButtonText: '<div class="text t1">ตกลง</div>',
                        allowOutsideClick: false, // Disable clicking outside popup to close
                        allowEscapeKey: false, // Disable ESC key to close
                        allowEnterKey: false // Disable Enter key to close
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "index";
                        }
                    });
                }, 1000); // Adjust timeout duration if needed
            </script>
        <?php
        }

        $stmt->close();
        $conn->close();
    } else if ($user_type == "owner") {
        // SQL สำหรับเพิ่มข้อมูลผู้ใช้ลงฐานข้อมูล
        $sql = "INSERT INTO owner (first_name, last_name, tel, email, password) VALUES (?, ?, ?, ?, ?)";

        // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $tel, $email, $hashed_password);

        if ($stmt->execute()) {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">สมัครใช้งานสำเร็จ</div>',
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
                }, 1000); // Adjust timeout duration if needed
            </script>
        <?php
        } else {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">เกิดข้อผิดพลาด</div>',
                        icon: 'error',
                        confirmButtonText: '<div class="text t1">ตกลง</div>',
                        allowOutsideClick: false, // Disable clicking outside popup to close
                        allowEscapeKey: false, // Disable ESC key to close
                        allowEnterKey: false // Disable Enter key to close
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "index";
                        }
                    });
                }, 1000); // Adjust timeout duration if needed
            </script>
<?php
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ระบบบริหารหอพัก</title>
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

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-4">สมัครใช้งาน</h3>
                        <form action="register.php" method="POST">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" required>
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="form-label">เบอร์โทรศัพท์</label>
                                <input type="tel" class="form-control" id="tel" name="tel" placeholder="Enter your phone number" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            </div>

                            <!-- เพิ่มตัวเลือกประเภทผู้ใช้ -->
                            <div class="mb-3">
                                <label class="form-label">ประเภทผู้ใช้งาน</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" id="tenant" value="tenant" required>
                                    <label class="form-check-label" for="tenant">
                                        ผู้เช่า
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" id="owner" value="owner" required>
                                    <label class="form-check-label" for="owner">
                                        เจ้าของหอพัก
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="index" class="btn btn-secondary">Login</a>
                            </div>
                        </form>
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