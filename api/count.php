<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $host = 'localhost';
    $username = 'u299560388_rfid';
    $password = 'Rfid2566';
    $dbname = 'u299560388_rfid';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error)));
    }

    $current_date = date('Y-m-d');
    $date_30_days_ago = date('Y-m-d', strtotime('-7 days'));
    $date_60_days_ago = date('Y-m-d', strtotime('-30 days'));
    $date_90_days_ago = date('Y-m-d', strtotime('-60 days'));

    // ดึงจำนวนการยืม
    $sql_borrowed = "SELECT COUNT(*) AS borrowed_count FROM tbl_check";
    $result_borrowed = $conn->query($sql_borrowed);
    $borrowed_count = $result_borrowed->fetch_assoc()['borrowed_count'];

    // ดึงจำนวนการคืน
    $sql_returned = "SELECT COUNT(*) AS returned_count FROM tbl_check WHERE status = '0'";
    $result_returned = $conn->query($sql_returned);
    $returned_count = $result_returned->fetch_assoc()['returned_count'];

    // คำนวณจำนวนที่ยังไม่ได้คืน
    $not_returned_count = $borrowed_count - $returned_count;

    // ดึงจำนวนการยืมตามช่วงวันที่
    $sql_borrowed_today = "SELECT COUNT(*) AS borrowed_count FROM tbl_check WHERE DATE(date_borrow) = '$current_date'";
    $sql_borrowed_7_days = "SELECT COUNT(*) AS borrowed_count FROM tbl_check WHERE DATE(date_borrow) BETWEEN '$date_7_days_ago' AND '$current_date'";
    $sql_borrowed_30_days = "SELECT COUNT(*) AS borrowed_count FROM tbl_check WHERE DATE(date_borrow) BETWEEN '$date_30_days_ago' AND '$current_date'";
    $sql_borrowed_60_days = "SELECT COUNT(*) AS borrowed_count FROM tbl_check WHERE DATE(date_borrow) BETWEEN '$date_60_days_ago' AND '$current_date'";

    $result_borrowed_today = $conn->query($sql_borrowed_today);
    $result_borrowed_7_days = $conn->query($sql_borrowed_7_days);
    $result_borrowed_30_days = $conn->query($sql_borrowed_30_days);
    $result_borrowed_60_days = $conn->query($sql_borrowed_60_days);

    $borrowed_today_count = $result_borrowed_today->fetch_assoc()['borrowed_count'];
    $borrowed_7_days_count = $result_borrowed_7_days->fetch_assoc()['borrowed_count'];
    $borrowed_30_days_count = $result_borrowed_30_days->fetch_assoc()['borrowed_count'];
    $borrowed_60_days_count = $result_borrowed_60_days->fetch_assoc()['borrowed_count'];

    // ดึงจำนวนการคืนตามช่วงวันที่
    $sql_returned_today = "SELECT COUNT(*) AS returned_count FROM tbl_check WHERE status = '0' AND DATE(date_return) = '$current_date'";
    $sql_returned_7_days = "SELECT COUNT(*) AS returned_count FROM tbl_check WHERE status = '0' AND DATE(date_return) BETWEEN '$date_7_days_ago' AND '$current_date'";
    $sql_returned_30_days = "SELECT COUNT(*) AS returned_count FROM tbl_check WHERE status = '0' AND DATE(date_return) BETWEEN '$date_30_days_ago' AND '$current_date'";
    $sql_returned_60_days = "SELECT COUNT(*) AS returned_count FROM tbl_check WHERE status = '0' AND DATE(date_return) BETWEEN '$date_60_days_ago' AND '$current_date'";

    $result_returned_today = $conn->query($sql_returned_today);
    $result_returned_7_days = $conn->query($sql_returned_7_days);
    $result_returned_30_days = $conn->query($sql_returned_30_days);
    $result_returned_60_days = $conn->query($sql_returned_60_days);

    $returned_today_count = $result_returned_today->fetch_assoc()['returned_count'];
    $returned_7_days_count = $result_returned_7_days->fetch_assoc()['returned_count'];
    $returned_30_days_count = $result_returned_30_days->fetch_assoc()['returned_count'];
    $returned_60_days_count = $result_returned_60_days->fetch_assoc()['returned_count'];

    // ดึงจำนวนการยังไม่คืนคืนตามช่วงวันที่
    $sql_not_returned_today = "SELECT COUNT(*) AS not_returned_count FROM tbl_check WHERE status = '1' AND DATE(date_borrow) = '$current_date'";
    $sql_not_returned_7_days = "SELECT COUNT(*) AS not_returned_count FROM tbl_check WHERE status = '1' AND DATE(date_borrow) BETWEEN '$date_7_days_ago' AND '$current_date'";
    $sql_not_returned_30_days = "SELECT COUNT(*) AS not_returned_count FROM tbl_check WHERE status = '1' AND DATE(date_borrow) BETWEEN '$date_30_days_ago' AND '$current_date'";
    $sql_not_returned_60_days = "SELECT COUNT(*) AS not_returned_count FROM tbl_check WHERE status = '1' AND DATE(date_borrow) BETWEEN '$date_60_days_ago' AND '$current_date'";

    $result_not_returned_today = $conn->query($sql_not_returned_today);
    $result_not_returned_7_days = $conn->query($sql_not_returned_7_days);
    $result_not_returned_30_days = $conn->query($sql_not_returned_30_days);
    $result_not_returned_60_days = $conn->query($sql_not_returned_60_days);

    $not_returned_today_count = $result_not_returned_today->fetch_assoc()['not_returned_count'];
    $not_returned_7_days_count = $result_not_returned_7_days->fetch_assoc()['not_returned_count'];
    $not_returned_30_days_count = $result_not_returned_30_days->fetch_assoc()['not_returned_count'];
    $not_returned_60_days_count = $result_not_returned_60_days->fetch_assoc()['not_returned_count'];

    // ดึงจำนวนการยืมคืน และยังไม่คืน แยกตามช่วงวันทั้งหมด
    $sql_all_borrowed = "SELECT COUNT(*) AS all_borrowed_count FROM tbl_check";
    $sql_all_returned = "SELECT COUNT(*) AS all_returned_count FROM tbl_check WHERE status = '0'";
    $sql_all_not_returned = "SELECT COUNT(*) AS all_not_returned_count FROM tbl_check WHERE status = '1'";

    $sql_borrowed_7_days = "SELECT COUNT(*) AS borrowed_7_days_count FROM tbl_check WHERE DATEDIFF(CURDATE(), date_borrow) <= 7";
    $sql_borrowed_30_days = "SELECT COUNT(*) AS borrowed_30_days_count FROM tbl_check WHERE DATEDIFF(CURDATE(), date_borrow) <= 30";
    $sql_borrowed_60_days = "SELECT COUNT(*) AS borrowed_60_days_count FROM tbl_check WHERE DATEDIFF(CURDATE(), date_borrow) <= 60";

    $result_all_borrowed = $conn->query($sql_all_borrowed);
    $result_all_returned = $conn->query($sql_all_returned);
    $result_all_not_returned = $conn->query($sql_all_not_returned);

    $result_borrowed_7_days = $conn->query($sql_borrowed_7_days);
    $result_borrowed_30_days = $conn->query($sql_borrowed_30_days);
    $result_borrowed_60_days = $conn->query($sql_borrowed_60_days);

    $all_borrowed_count = $result_all_borrowed->fetch_assoc()['all_borrowed_count'];
    $all_returned_count = $result_all_returned->fetch_assoc()['all_returned_count'];
    $all_not_returned_count = $result_all_not_returned->fetch_assoc()['all_not_returned_count'];

    $borrowed_7_days_count = $result_borrowed_7_days->fetch_assoc()['borrowed_7_days_count'];
    $borrowed_30_days_count = $result_borrowed_30_days->fetch_assoc()['borrowed_30_days_count'];
    $borrowed_60_days_count = $result_borrowed_60_days->fetch_assoc()['borrowed_60_days_count'];

    $response = array(
        'status' => 'success',
        'borrowed_count' => (int) $borrowed_count,
        'returned_count' => (int) $returned_count,
        'not_returned_count' => (int) $not_returned_count,
        'borrowed_today_count' => (int) $borrowed_today_count,
        'borrowed_7_days_count' => (int) $borrowed_7_days_count,
        'borrowed_30_days_count' => (int) $borrowed_30_days_count,
        'borrowed_60_days_count' => (int) $borrowed_60_days_count,
        'returned_today_count' => (int) $returned_today_count,
        'returned_7_days_count' => (int) $returned_7_days_count,
        'returned_30_days_count' => (int) $returned_30_days_count,
        'returned_60_days_count' => (int) $returned_60_days_count,
        'not_returned_today_count' => (int) $not_returned_today_count,
        'not_returned_7_days_count' => (int) $not_returned_7_days_count,
        'not_returned_30_days_count' => (int) $not_returned_30_days_count,
        'not_returned_60_days_count' => (int) $not_returned_60_days_count,
        'all_borrowed_count' => (int) $all_borrowed_count,
        'all_returned_count' => (int) $all_returned_count,
        'all_not_returned_count' => (int) $all_not_returned_count,
        'borrowed_7_days_count' => (int) $borrowed_7_days_count,
        'borrowed_30_days_count' => (int) $borrowed_30_days_count,
        'borrowed_60_days_count' => (int) $borrowed_60_days_count
    );

    echo json_encode($response);

    $conn->close();
} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}
?>
