<?php
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

$month1 = date('F Y');
// echo thaiDate($month1); // จะแสดงผลเป็นเดือนภาษาไทยพร้อมปี พ.ศ.

if (isset($_GET['email'])) {
    $email = $_GET['email'];   //เมล
    $id_room = $_GET['id_room']; //เลขห้อง
    $f_name = $_GET['f_name'];  //ชื่อ - สกุล
    $wc = $_GET['wc']; //ค่าน้ำ
    $we = $_GET['we']; //ค่าไฟ
    $cr = $_GET['cr']; //ค่าห้อง
    $total = $_GET['total']; // รวม
}
// รวมไฟล์ PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // ตั้งค่าเซิร์ฟเวอร์ SMTP
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'botpcnone@gmail.com';
    $mail->Password   = 'rvda fhah qwxq smab';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // ตั้งค่าข้อมูลอีเมล
    $mail->setFrom('bot@pcnone.com', 'ระบบแจ้งเตือนหอพัก');
    $mail->addAddress($email, 'Recipient Name');
    $mail->isHTML(true);
    $mail->Subject = 'ใบแจ้งหนี้ของหอพัก ห้อง ' . $id_room;
    $mail->Body    = 'ประจำเดือน ' . thaiDate($month1) . '<br>
    ค่าห้อง <b>' . $cr . ' </b>บาท<br>
    ค่าน้ำ <b>' . $wc . ' </b>บาท<br>
    ค่าไฟ <b>' . $we . ' </b>บาท<br>
    รวม <b>' . $total . ' </b>บาท<br>
    <b> กรุณาชำระเงินภายในวันที่ 10 ' . thaiDate($month1) . '</b><br>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo '<script type="text/javascript">
            alert("Message has been sent");
            window.close();
          </script>';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
