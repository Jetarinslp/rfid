<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $host = 'localhost';
    $username = 'u299560388_rfid';
    $password = 'Rfid2566';
    $dbname = 'u299560388_rfid';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error)));
    }

    $tag_id = trim($_POST['tag_id']);
    $status = trim($_POST['status']);
    $tablet_id = trim($_POST['tablet_id']);

    $create_date = date('Y-m-d H:i:s');

    $sql_check = "SELECT * FROM tag WHERE tag_id = '$tag_id' OR tablet_id = '$tablet_id'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo json_encode(array('status' => 'error', 'message' => 'RFID tag or serial number already exists.'));
    } else {
        // Insert new device entry
        $sql = "INSERT INTO tag (tag_id, status, tablet_id, create_date) VALUES ('$tag_id', '$status', '$tablet_id', '$create_date')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(array('status' => 'success', 'message' => 'Device added successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error: ' . $sql . '<br>' . $conn->error));
        }
    }
    
    $conn->close();

} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}
?>
