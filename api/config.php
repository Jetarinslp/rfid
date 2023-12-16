<?php

    $host = 'localhost';
    $username = 'u299560388_rfid';
    $password = 'Rfid2566';
    $dbname = 'u299560388_rfid';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

?>