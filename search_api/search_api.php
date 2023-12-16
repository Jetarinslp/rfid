<?php 

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
        
    $data = json_decode(file_get_contents("php://input"), true);

    $search_value = $data['search'];

    include "config.php";

    $sql = "SELECT * FROM tbl_check WHERE tag_id LIKE '%{$search_value}%' OR id_student LIKE '%{$search_value}%'";

    $result = mysqli_query($conn, $sql) or die("Failed");

    if(mysqli_num_rows($result) > 0) {
        $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($output);
    } else {
        echo json_encode(array('message' => 'No search found', 'status' => false));
    }
                    
?>