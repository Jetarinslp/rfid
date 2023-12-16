<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Header: Content-Type, Access-Control-Allow-Headers,Authorization, X-Requested-With");
                
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $data = json_decode(file_get_contents("php://input"), true);

        include "config.php";

        $sql = "SELECT tbl_check.`id`, tbl_check.`id_student`, tbl_check.`tag_id`, tag.tablet_id, tbl_check.`status` , tbl_check.`date_borrow`, tbl_check.`date_return`
        FROM `tbl_check` INNER JOIN tag ON tbl_check.tag_id = tag.tag_id ORDER BY id DESC";

        $result = mysqli_query($conn, $sql) or die("Failed");

        if(mysqli_num_rows($result) > 0) {
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            
            echo json_encode(array('message' => 'Not found', 'status' => 'success', 'data' => $output));
        } else {
            echo json_encode(array('message' => 'No information', 'status' => 'failed', 'data' => []));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
    }
?>