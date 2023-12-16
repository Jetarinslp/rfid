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

    // รับค่า tag_id, tablet_id, create_date จากพารามิเตอร์ GET ถ้ามี
    $tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : null;
    $tablet_id = isset($_GET['tablet_id']) ? $_GET['tablet_id'] : null;
    $create_date = isset($_GET['create_date']) ? $_GET['create_date'] : null;

    $sql = "SELECT * FROM `tag` WHERE 1=1";
    if (!is_null($tablet_id)) {
        $sql .= " AND tablet_id LIKE '%$tablet_id%'";
    }

    if (!is_null($tag_id)) {
        $sql .= " AND tag_id LIKE '%$tag_id%'";
    }

    if (!is_null($create_date)) {
        $sql .= " AND DATE(create_date) LIKE '%$create_date%'";
    }

    $sql .= " ORDER BY create_date DESC LIMIT $row_page OFFSET $offset";
    /* // สร้างเงื่อนไข WHERE โดยเช็ค tag_id, tablet_id, create_date ถ้ามี
    $where = '';
    if (!is_null($tag_id)) {
        $where .= " tag_id = $tag_id AND";
    }
    if (!is_null($tablet_id)) {
        $where .= " tablet_id = $tablet_id AND";
    }
    if (!is_null($create_date)) {
        // เพิ่มเงื่อนไขสำหรับ create_date
        //$where .= " DATE(create_date) = '$create_date' AND";
        //$where .= " create_date = '$create_date' AND";
        $where .= " AND DATE(tag.create_date) = '$create_date'";
    }
    if (!empty($where)) {
        $where = 'WHERE ' . rtrim($where, ' AND');
    }

    $sql = "SELECT * FROM `tag` $where ORDER BY create_date DESC LIMIT $row_page OFFSET $offset"; */
    

    $result = mysqli_query($conn, $sql) or die("Failed");

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $totalCountSql = "SELECT COUNT(*) as total FROM tag WHERE 1=1";
        if (!is_null($tablet_id)) {
            $totalCountSql .= " AND tablet_id LIKE '%$tablet_id%'";
        }
    
        if (!is_null($tag_id)) {
            $totalCountSql .= " AND tag_id LIKE '%$tag_id%'";
        }
    
        if (!is_null($create_date)) {
            $totalCountSql .= " AND DATE(create_date) LIKE '%$create_date%'";
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
        $pagination = array(
            'total' => 0,
            'page' => $page,
            'itemsPerPage' => $row_page,
            'totalPages' => 0
        );
        echo json_encode(array('status' => 'success', 'data' => [], 'pagination' => $pagination));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => $_SERVER['REQUEST_METHOD'] . ' Method not allowed.'));
}
?>
