<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated tag_id from the POST data
    $updated_tag_id = trim($_POST['tag_id']);

    $host = 'localhost';
    $username = 'u299560388_rfid';
    $password = 'Rfid2566';
    $dbname = 'u299560388_rfid';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error)));
    }

    // Check if the RFID tag exists
    $sql_check = "SELECT * FROM tag WHERE tag_id = '$updated_tag_id'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows === 0) {
        echo json_encode(array('status' => 'error', 'message' => 'RFID tag does not exist.'));
    } else {
        
        $new_tag_id = trim($_POST['new_tag_id']);
        $sql_update = "UPDATE tag SET tag_id = '$new_tag_id' WHERE tag_id = '$updated_tag_id'";
        
        if ($conn->query($sql_update) === TRUE) {
            echo json_encode(array('status' => 'success', 'message' => 'Tag_id updated successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error updating tag_id: ' . $conn->error));
        }
    }

    $conn->close();
} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}
?>
