<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = 'localhost';
    $username = 'u299560388_rfid';
    $password = 'Rfid2566';
    $dbname = 'u299560388_rfid';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(array('status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error)));
    }

    $sql = "SELECT email FROM tbl_check WHERE status = '1' AND status_email = '0'";
    $result = $conn->query($sql);

    if ($result) {
        $emails = array();

        while ($row = $result->fetch_assoc()) {
            $emails[] = $row['email'];
        }

        if (count($emails) > 0) {
            $returnDate = date('Y-m-d', strtotime('+1 day'));
            $strSubject = "Test Send Email";
            $strHeader = "From: ReclaimEquipment@rmutp.ac.th";
            $strMessage = "กำหนดการคืนอุปกรณ์ กรุณาคืนภายใน: $returnDate";

            $successCount = 0;

            for ($i = 0; $i < count($emails); $i++) {
                $strTo = $emails[$i];
                $flgSend = @mail($strTo, $strSubject, $strMessage, $strHeader);
                if ($flgSend) {
                    $successCount++;

                    $updateSql = "UPDATE tbl_check SET status_email = '1' WHERE email = '$strTo'";
                    $updateResult = $conn->query($updateSql);
                }
            }

            if ($successCount > 0) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Email Sending.',
                    'success_count' => $successCount,
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'No Email',
                );
            }
            echo json_encode($response);
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'No emails to send.'));
        }

        $conn->close();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Database Error: ' . $conn->error));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}
?>
