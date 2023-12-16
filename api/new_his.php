<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        include "config.php";
        
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $row_page = 20;
            $offset = ($page - 1) * $row_page;

            $tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : null;
            $id_student = isset($_GET['id_student']) ? $_GET['id_student'] : null;
            $date = isset($_GET['date']) ? $_GET['date'] : null;

            $sql = "SELECT tbl_check.id, tbl_check.id_student, tbl_check.tag_id, tag.tablet_id, tbl_check.status, tbl_check.date_borrow, tbl_check.date_return
            FROM tbl_check
            INNER JOIN tag ON tbl_check.tag_id = tag.tag_id
            WHERE 1=1"; // Starting the WHERE clause

            if (!is_null($tag_id)) {
                $sql .= "  AND tbl_check.tag_id LIKE '%$tag_id%'";
            }

            if (!is_null($id_student)) {
                $sql .= " AND tbl_check.id_student LIKE '%$id_student%'";
            }

            if (!is_null($date)) {
                $sql .= " AND (DATE(tbl_check.date_borrow) LIKE '%$date%' OR DATE(tbl_check.date_return) LIKE '%$date%')";
            }

            $sql .= " ORDER BY tbl_check.id DESC
                    LIMIT $row_page OFFSET $offset";

            $result = mysqli_query($conn, $sql) or die("Failed");


            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                
                $totalCountSql = "SELECT COUNT(*) as total FROM tbl_check WHERE 1=1";

                if (!is_null($tag_id)) {
                    $totalCountSql .= " AND tbl_check.tag_id LIKE '%$tag_id%'";
                }

                if (!is_null($id_student)) {
                    $totalCountSql .= " AND tbl_check.id_student LIKE '%$id_student%'";
                }

                if (!is_null($date)) {
                    $totalCountSql .= " AND (DATE(tbl_check.date_borrow) LIKE '%$date%' OR DATE(tbl_check.date_return) LIKE '%$date%')";
                }

                $totalCountResult = $conn->query($totalCountSql);
                $totalCountRow = $totalCountResult->fetch_assoc();
                $totalCount = intval($totalCountRow['total']);

                $pagination = array(
                    'total' => $totalCount,
                    'page' => $page,
                    'itemsPerPage' => $row_page,
                    'totalPages' => ceil($totalCount / $row_page)
                );

                echo json_encode(array('status' => 'success', 'data' => $data, 'pagination' => $pagination));
            } else {
                echo json_encode(array('message' => 'No data found.', 'data' => [], 'pagination' => array(
                    'total' => 0, 'page' => $page, 'row_page' => $row_page, 'totalPages' => 0
                )));
            }
       
    } else {
        echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
    }

?>