<?php
    //require('./db.php');

    if($_SERVER['REQUEST_METHOD'] == "POST") {

      $host = 'localhost';
      $username = 'u299560388_rfid';
      $password = 'Rfid2566';
      $dbname = 'u299560388_rfid';

      try {
          $db = new PDO("mysql:host={$db_host}; dbname={$db_name}", $db_user, $db_password );
          $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch(PDOException $e) {
          $e->getMessage();
      }

        $items_arr['result'] = array();

        $stdNo = $_POST['stdNo']; //keywordที่พิมพ์มา
        $tagNo = $_POST['tagNo']; //ฟิลล์ในdb

      if($tagNo =="stdNo"){
        $tagNo="id_student";
      }else if($tagNo=="tagNo"){
        $tagNo="tag_id";
      }

        $query = "SELECT * FROM tbl_check WHERE `$tagNo` LIKE '%$stdNo%' ";
        $stmt = $db->prepare($query);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row); //จะได้ฟิลล์ทุกตัวในdb
            array_push($items_arr['result'], $row);
          }
        echo json_encode($items_arr);
        http_response_code(200);


    }
?>