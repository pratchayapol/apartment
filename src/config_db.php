<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "100.99.99.99:3309"; // ปรับเป็นค่าของคุณ
$username = "root"; // ปรับเป็นค่าของคุณ
$dbname = "rental_payment_system"; // ปรับเป็นชื่อฐานข้อมูลของคุณ
$password_db = "adminrmuti"; // 

$conn = new mysqli($servername, $username, $password_db, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
