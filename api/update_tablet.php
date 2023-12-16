<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Header: Content-Type, Access-Control-Allow-Headers,Authorization, X-Requested-With");
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        $tag_id = $data['tag_id'];
        $tablet_id = $data['tablet_id'];

        include "config.php";
        
        $sql1 = "SELECT * FROM tag WHERE `tablet_id` = '{$tablet_id}' AND `tag_id` = '{$tag_id}'";
        $result1 = mysqli_query($conn, $sql1);
        $num1 = mysqli_num_rows($result1);

        // check dupicate
        if($num1 == 0) {
            $sql2 = "SELECT * FROM tag WHERE `tablet_id` = '{$tablet_id}'";
            $result2 = mysqli_query($conn, $sql2);
            $num2 = mysqli_num_rows($result2);

            // can update
            if($num2 == 0) {
                $sql = "UPDATE `tag` SET `tablet_id` = '{$tablet_id}'  WHERE `tag`.`tag_id` = '{$tag_id}'";
                echo json_encode(array('message' => 'Update success', 'status' => true));
                $result = mysqli_query($conn, $sql) or die ("Error in query" );
            } else { //$num = 1
                echo json_encode(array('message' => 'Serial Number already exists', 'status' => false));
            }
        } elseif ($num1 == 1) { //chk $sql1 
            echo json_encode(array('message' => 'Serial Number already used', 'status' => false));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
    }

?>