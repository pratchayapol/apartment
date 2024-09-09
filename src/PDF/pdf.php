<?php
require('fpdf.php');
include "../config_db.php";
date_default_timezone_set('Asia/Bangkok');

if (isset($_GET['email'])) {
    $email = $_GET['email'];   //เมล
    $id_room = $_GET['id_room']; //เลขห้อง
    $f_name = $_GET['f_name'];  //ชื่อ - สกุล
    $wc = $_GET['wc']; //ค่าน้ำ
    $we = $_GET['we']; //ค่าไฟ
    $cr = $_GET['cr']; //ค่าห้อง
    $total = $_GET['total']; // รวม
    $my = 'ประจำเดือน '.$_GET['my']; // เดือน ปีพศ
    $name_ap = $_GET['name_ap']; // ชื่อหอพัก

    $wc1 = $_GET['wc1']; //เลขน้ำก่อนหน้า
    $wc2 = $_GET['wc2']; //เลขน้ำล่าสุด
    $wc3 = $_GET['wc3']; //น้ำที่ใช้ไป

    $we1 = $_GET['we1']; //เลขไฟก่อนหน้า
    $we2 = $_GET['we2']; //เลขไฟล่าสุด
    $we3 = $_GET['we3']; //ไฟที่ใช้ไป
}


$pdf = new FPDF();
$pdf->AddPage('P');
$pdf->AddFont('sara', '', 'THSarabun.php');
$pdf->Image('bg.jpg', 0, 0, 210, 297);
$pdf->SetXY(190, 0);

//ประจำเดือน
$pdf->SetY(45);
$pdf->SetX(85);
$pdf->SetFont('sara', '', 24);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $my), 0, 1, 'C');

//ชือหอพัก
$pdf->SetY(56);
$pdf->SetX(45);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $name_ap), 0, 1, 'L');

//เลขห้อง
$pdf->SetY(56);
$pdf->SetX(135);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $id_room), 0, 1, 'L');

//ชื่อผู้เช่า
$pdf->SetY(68);
$pdf->SetX(45);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $f_name), 0, 1, 'L');

//ค่าห้อง
$pdf->SetY(88.5);
$pdf->SetX(151.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $cr), 0, 1, 'C');

//ค่าน้ำ
$pdf->SetY(95.5);
$pdf->SetX(151.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $wc), 0, 1, 'C');

//เลขน้ำก่อนหน้า
$pdf->SetY(95.5);
$pdf->SetX(48.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $wc1), 0, 1, 'C');

//เลขน้ำล่าสุด
$pdf->SetY(95.5);
$pdf->SetX(72.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $wc2), 0, 1, 'C');

//เลขน้ำที่ใช้
$pdf->SetY(95.5);
$pdf->SetX(100.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $wc3), 0, 1, 'C');

//น้ำหน่วยละ
$pdf->SetY(95.5);
$pdf->SetX(127.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $wc4), 0, 1, 'C');


//ค่าไฟ
$pdf->SetY(103.5);
$pdf->SetX(151.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $we), 0, 1, 'C');

//เลขไฟก่อนหน้า
$pdf->SetY(103.5);
$pdf->SetX(48.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $we1), 0, 1, 'C');

//เลขไฟล่าสุด
$pdf->SetY(103.5);
$pdf->SetX(72.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $we2), 0, 1, 'C');

//เลขไฟที่ใช้
$pdf->SetY(103.5);
$pdf->SetX(100.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $we3), 0, 1, 'C');

//ไฟหน่วยละ
$pdf->SetY(103.5);
$pdf->SetX(127.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $we4), 0, 1, 'C');

//ผลรวม
$pdf->SetY(111.5);
$pdf->SetX(151.5);
$pdf->SetFont('sara', '', 18);
$pdf->Cell(40, 2, iconv('utf-8', 'cp874', $total), 0, 1, 'C');

$pdf->Output();
