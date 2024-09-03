<?php
session_start(); // เริ่มการจัดการเซสชัน

// ลบข้อมูลทั้งหมดในเซสชัน
session_unset();

// ทำลายเซสชัน
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
header("Location: index");
exit();
?>
