<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Header: Content-Type, Access-Control-Allow-Headers,Authorization, X-Requested-With");
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $id_student = $data['id_student'];
        $email = $data['email'];

        include "config.php";

        if (isset($email) && !empty($email)) {
            $sql = "UPDATE tbl_check SET id_student = '{$id_student}', `email` = '{$email}', status_email = '0' WHERE id = '{$id}'";
        } else {
            $sql = "UPDATE tbl_check SET id_student = '{$id_student}', `email` = NULL, status_email = '0' WHERE id = '{$id}'";
        }
        if(mysqli_query($conn, $sql)) {
            $affectedRows = mysqli_affected_rows($conn);

            if ($affectedRows > 0) {
                echo json_encode(array('message' => 'Update success', 'status' => true));
            } else {
                echo json_encode(array('message' => 'No records updated', 'status' => false));
            }
        } else {
            echo json_encode(array('message' => 'Update failed', 'status' => false));
        }

    } else {
        echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
    }

    
        /* $sql = "UPDATE tbl_check SET id_student = '{$id_student}', `email` = '{$email}', status_email = '1' WHERE id = '{$id}'";
        $result = mysqli_query($conn, $sql) or die("Failed");
        
        if(mysqli_query($conn, $sql)) {
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode(array('message' => 'Update success', 'status' => true));
        } else {
            echo json_encode(array('message' => 'Update failed', 'status' => false));
        } */

?>