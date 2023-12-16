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

    $date_borrow = date('Y-m-d H:i:s');
    $date_return = date('Y-m-d H:i:s'); 

    $sql_check = "SELECT * FROM tag WHERE tag_id = '$tag_id' AND status_br = '1'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $tag_id = $row['tag_id'];

        $sql_update = "UPDATE tbl_check SET status = '0', date_return = '$date_return' WHERE tag_id = '$tag_id' AND status = '1'";

        if ($conn->query($sql_update) == TRUE) {
            $sql_update_email = "UPDATE tbl_check SET status_email = '0' WHERE tag_id = '$tag_id'";
            $conn->query($sql_update_email);
            echo json_encode(array('status' => 'success', 'message' => 'Device returned successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error updating record: ' . $conn->error));
        }
        $sql_update = "UPDATE tag SET status_br = '0' WHERE tag_id = '$tag_id'";
        $conn->query($sql_update);
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No borrowed device.'));
    }

    $conn->close();
} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}
?>
