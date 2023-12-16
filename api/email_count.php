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

    $sql_sent_email_count = "SELECT COUNT(*) AS sent_email_count FROM tbl_check WHERE status = '1' AND status_email	= '0' AND (email IS NOT NULL AND email <> '')";
    $result_sent_email_count = $conn->query($sql_sent_email_count);
    $sent_email_count = $result_sent_email_count->fetch_assoc()['sent_email_count'];

    $sql_unsent_email_count = "SELECT COUNT(*) AS unsent_email_count FROM tbl_check WHERE status = '1' AND (email IS NULL OR email = '')";
    $result_unsent_email_count = $conn->query($sql_unsent_email_count);
    $unsent_email_count = $result_unsent_email_count->fetch_assoc()['unsent_email_count'];

    $response = array(
        'status' => 'success',
        'sent_email_count' => (int) $sent_email_count,
        'unsent_email_count' => (int) $unsent_email_count,
    );

    echo json_encode($response);

    $conn->close();
} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}
?>
