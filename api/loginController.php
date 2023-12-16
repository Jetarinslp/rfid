<?php

header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Header: Content-Type,Access-Control-Allow-Headers,Authorization, X-Requested-With");
    header("Content-Type: application/json");

    
// ตรวจสอบว่าเป็นการเรียกใช้งานด้วย method POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $host = 'localhost';
    $username = 'u299560388_rfid';
    $password = 'Rfid2566';
    $dbname = 'u299560388_rfid';

    $conn = new mysqli($host, $username, $password, $dbname);

    // ตรวจสอบว่าเชื่อมต่อฐานข้อมูลสำเร็จหรือไม่
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // รับข้อมูลที่ส่งมาจากผู้ใช้
    $username = $_POST['username'];
    $password = $_POST['password'];

    // เตรียมคำสั่ง SQL สำหรับค้นหาข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT * FROM login WHERE username = '$username' AND password = '$password'";

    // ทำการค้นหาข้อมูล
    $result = $conn->query($sql);

    // ตรวจสอบว่ามีข้อมูลผู้ใช้ที่ตรงกับเงื่อนไขหรือไม่
    if ($result->num_rows > 0) {
        // ถ้ามีข้อมูลผู้ใช้ ให้คืนค่า success
        $user = $result->fetch_assoc();
        $response = array(
            'status' => 'success',
            'user' => $user
        );
        echo json_encode($response);
    } else {
        // ถ้าไม่พบข้อมูลผู้ใช้ ให้คืนค่า error
        $response = array(
            'status' => 'error',
            'message' => 'Username or password is incorrect'
        );
        echo json_encode($response);
    }

    $conn->close();
} else {
    // ถ้าไม่ใช้ method POST ให้แสดงข้อความว่าไม่อนุญาติให้เข้าถึง API นี้
    echo 'Method not allowed.';
}
?>
