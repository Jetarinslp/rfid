<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

// ตรวจสอบว่าเป็นการเรียกใช้งานด้วย method GET หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['search'])) {
        $searchValue = $_GET['search'];
        
        $host = 'localhost';
        $username = 'u299560388_rfid';
        $password = 'Rfid2566';
        $dbname = 'u299560388_rfid';

        $conn = new mysqli($host, $username, $password, $dbname);

        if ($conn->connect_error) {
            die(json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error)));
        }

        $itemsPerPage = 20;
 
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        $offset = ($page - 1) * $itemsPerPage;

        // ค้นหาข้อมูลในตาราง tbl_check โดยการกำหนด LIMIT และ OFFSET
        $sql = "SELECT * FROM tbl_check WHERE tag_id LIKE '%$searchValue%' ORDER BY date_borrow DESC LIMIT $itemsPerPage OFFSET $offset";
   
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            // นับจำนวนรายการทั้งหมดในการค้นหา
            $totalCountSql = "SELECT COUNT(*) as total FROM tbl_check WHERE tag_id LIKE '%$searchValue%'";
            $totalCountResult = $conn->query($totalCountSql);
            $totalCountRow = $totalCountResult->fetch_assoc();
            $totalCount = intval($totalCountRow['total']);

            $pagination = array(
                'total' => $totalCount,
                'page' => $page,
                'itemsPerPage' => $itemsPerPage,
                'totalPages' => ceil($totalCount / $itemsPerPage)
            );

            echo json_encode(array('status' => 'success', 'data' => $data, 'pagination' => $pagination));
        } else {
            echo json_encode(array('status' => 'success', 'message' => 'No data found.'));
        }

        $conn->close();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Missing search parameter.' ));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}

?>
