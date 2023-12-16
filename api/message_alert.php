<?
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Header: Content-Type, Access-Control-Allow-Headers,Authorization, X-Requested-With");

    include "config.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $strTo = $data['email'];
    
        // ค้นหาอีเมลในฐานข้อมูลที่มีสถานะเป็น 0
        $sql_check_email = "SELECT * FROM tbl_check WHERE email = '{$strTo}' AND status_email = '0'";
        $result_check_email = mysqli_query($conn, $sql_check_email);
    
        if (mysqli_num_rows($result_check_email) > 0) {
            // หากมีอีเมลอยู่ในฐานข้อมูลและมีสถานะเป็น 0
            $row = mysqli_fetch_assoc($result_check_email);
    
            // ตั้งค่าข้อมูลสำหรับส่งอีเมล
            $returnDate = date('Y-m-d', strtotime('+1 day'));
            $strSubject = "Test Send Email";
            $strHeader = "From: sarinya150544k@gmail.com";
            $strMessage = "กำหนดการคืนอุปกรณ์ กรุณาคืนภายใน: $returnDate";
    
            // ส่งอีเมล
            $flgSend = @mail($strTo, $strSubject, $strMessage, $strHeader);  // @ = No Show Error 
    
            if ($flgSend) {
                // อัปเดตสถานะอีเมลเป็นส่งแล้ว
                $sql_update_status = "UPDATE tbl_check SET status_email = '1' WHERE email = '{$strTo}'";
                if (mysqli_query($conn, $sql_update_status)) {
                    $status_email = 1;
                    echo json_encode(array('status' => 'success', 'message' => 'Email Sending.', 'status_email' => $status_email));

                    // ตั้งค่าวันถัดไป
                    $nextDay = date('Y-m-d', strtotime('+1 day'));
                    $sql_set_next_day = "UPDATE tbl_check SET status_email = '0' WHERE email = '{$strTo}' AND DATE_ADD(date_sent, INTERVAL 1 DAY) = '$nextDay'";
                    
                    // เช็คว่า status_return เป็น 0 หรือไม่
                    if ($row['status_return'] == 0) {
                        mysqli_query($conn, $sql_set_next_day);
                    }

                } else {
                    echo json_encode(array('status' => false, 'message' => 'Failed to update email status'));
                }
            } else {
                $status_email = 0;
                echo json_encode(array('status' => false, 'message' => 'Failed to send email', 'status_email' => $status_email));
            }
        } else {
            echo json_encode(array('status' => false, 'message' => 'Email not found in database or already sent.'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
    }
    
?>