<?php

    $host = 'localhost';
    $username = 'surache1_rfid';
    $password = 'rfidrfid';
    $dbname = 'surache1_rfid';

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

?>