<?php
    require_once 'connection.php';

    Class tblYears extends connection{

        public function getAll(){
            $sql = "";
            
            $sql = "SELECT * FROM year ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $response =  $stmt->fetchAll();

            return $response;
        }
        public function get($id){
            $sql = "";
            
            $sql = "SELECT * FROM year WHERE id=:id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["id"=>$id]);
            $response =  $stmt->fetch();

            return $response;
        }

        public function insert($name,$description){

            $sql = "INSERT INTO year (name,description) ";
            $sql .= "VALUES(:name,:description);";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["name"=>$name,"description"=>$description]);

            $response =  $this->conn->lastInsertId();

            return $response;
        }

        public function update($id,$name,$description){

            $sql = "UPDATE year SET ";
            $sql .= " name=:name ,";
            $sql .= " description=:description ";
            $sql .= " WHERE id=:id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["id"=>$id,"name"=>$name,"description"=>$description]);

            return $id;
        }

        public function delete($id){
            $sql = "DELETE FROM year ";
            $sql .= " WHERE id=:id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["id"=>$id]);

            return $id;
        }
    }
?>