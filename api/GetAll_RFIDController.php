<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

// ตรวจสอบว่าเป็นการเรียกใช้งานด้วย method GET หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $host = 'localhost';
    $username = 'u299560388_rfid';
    $password = 'Rfid2566';
    $dbname = 'u299560388_rfid';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error)));
    }

    // เตรียมคำสั่ง SQL สำหรับดึงข้อมูลทั้งหมดจากตาราง
    $sql = "SELECT * FROM tag ORDER BY create_date DESC";
    //$sql = "SELECT * FROM tag";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $devices = array();
        while ($row = $result->fetch_assoc()) {
            $devices[] = $row;
        }
        echo json_encode(array('status' => 'success', 'devices' => $devices));
    } else {
        echo json_encode(array('status' => 'success', 'message' => 'No devices found.'));
    }

    $conn->close();
} else {
    // ถ้าไม่ใช้ method GET ให้แสดงข้อความว่าไม่อนุญาติให้เข้าถึง API นี้
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}

?>
