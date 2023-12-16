<?php
class connection {

    private $db_server = 'localhost';
    private $db_port = '3306';
    private $db_database = 'surache1_rfid';

    private $db_user = 'surache1_rfid';
    private $db_password = 'rfidrfid';


    protected $conn = null;

    /*public function __construct(){
        try{

            $this->conn = new PDO('mysql:host='.$this->db_server.';port='.$this->db_port.';dbname='.$this->db_database.';charset=utf8',$this->db_user,$this->db_password);

            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);
            $this->conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        }catch( PDOException $ex){
            die('connection failed: '.$ex->getMessage());
        }

    }*/
}

?>