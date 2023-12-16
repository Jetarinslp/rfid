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

    $sql_select = "SELECT * FROM tag WHERE tag_id = '$tag_id'";
    $result = $conn->query($sql_select);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row['status_br'];

            if ($status == '1') {
                echo json_encode(array('status' => 'error', 'message' => 'Device already borrowed.'));
            } else {
                $id_student = trim($_POST['id_student']);
                $email = trim($_POST['email']);
                $status = '1';
                $date_borrow = date('Y-m-d H:i:s');
                $sql_insert = "INSERT INTO tbl_check (tag_id, id_student, email, status, date_borrow) VALUES ('$tag_id', '$id_student', '$email', '$status', '$date_borrow')";

                if ($conn->query($sql_insert) === TRUE) {
                    echo json_encode(array('status' => 'success', 'message' => 'Device borrowed successfully.'));
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Error: ' . $sql_insert . '<br>' . $conn->error));
                }

                $sql_update = "UPDATE tag SET status_br = '1' WHERE tag_id = '$tag_id'";
                $conn->query($sql_update);
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Tag not found. Please check the tag ID.'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error: ' . $sql_select . '<br>' . $conn->error));
    }

    $conn->close();
} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}
?>
