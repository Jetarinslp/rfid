<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Header: Content-Type, Access-Control-Allow-Headers,Authorization, X-Requested-With");
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        $tag = $data['tag_id'];

        include "config.php";

        $sql = "DELETE FROM tag WHERE tag_id = '{$tag}'";

        if(mysqli_query($conn, $sql)) {
            echo json_encode(array('message' => 'Delete success.', 'status' => true));
        } else {
            echo json_encode(array('message' => 'The equipment has been borrowed.', 'status' => false));
        } 
    } else {
        echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
    }

?> 