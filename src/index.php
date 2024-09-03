<?php
session_start();
include "./config_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ตรวจสอบค่า (ตัวอย่างการตรวจสอบแบบง่าย)
    if (!empty($email) && !empty($password)) {
        // ตรวจสอบข้อมูลจากตาราง owner
        $sql = "SELECT * FROM owner WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // มีข้อมูลในตาราง owner
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // ตั้งค่า session และเปลี่ยนเส้นทาง
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'owner'; // กำหนดประเภทผู้ใช้
                header("Location: owner/index"); // ไปยังหน้าหลังจาก login สำเร็จ
                exit();
            } else {
                echo "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            // ตรวจสอบข้อมูลจากตาราง tenant
            $sql = "SELECT * FROM tenant WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // มีข้อมูลในตาราง tenant
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // ตั้งค่า session และเปลี่ยนเส้นทาง
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = 'tenant'; // กำหนดประเภทผู้ใช้
                    header("Location: tenant/index"); // ไปยังหน้าหลังจาก login สำเร็จ
                    exit();
                } else {
                    echo "รหัสผ่านไม่ถูกต้อง";
                }
            } else {
                echo "ไม่พบผู้ใช้";
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "กรุณากรอกอีเมลและรหัสผ่าน";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Management System Login</title>
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Login - ระบบบริหารหอพัก</h3>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>

                        <!-- เพิ่มปุ่มสมัครใช้งาน -->
                        <div class="text-center mt-3">
                            <a href="register" class="btn btn-secondary">สมัครใช้งาน</a>
                        </div>
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