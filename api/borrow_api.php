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
    $tablet_id = isset($_GET['tablet_id']) ? $_GET['tablet_id'] : null;
    $create_date = isset($_GET['create_date']) ? $_GET['create_date'] : null;

    $where = "WHERE tbl_check.status = '1'";

    if (!is_null($tag_id)) {
        $where .= " AND tbl_check.tag_id LIKE '%$tag_id%'";
    }

    if (!is_null($tablet_id)) {
        $where .= " AND tag.tablet_id LIKE '%$tablet_id%'";
    }

    if (!is_null($create_date)) {
        $where .= " AND DATE(tbl_check.date_borrow) LIKE '%$create_date%'";
    }

    $sql = "SELECT tbl_check.`id`, tbl_check.`tag_id`, tag.tablet_id, tbl_check.`id_student`, tbl_check.`email`, tbl_check.`status_email`, tbl_check.`status`, tbl_check.`date_borrow`, tbl_check.`date_return`
        FROM tbl_check INNER JOIN tag ON tbl_check.tag_id = tag.tag_id $where ORDER BY tbl_check.date_borrow DESC LIMIT $row_page OFFSET $offset";

    $result = mysqli_query($conn, $sql) or die("Failed");

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $totalCountSql = "SELECT COUNT(*) as total FROM tbl_check INNER JOIN tag ON tbl_check.tag_id = tag.tag_id $where";
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
