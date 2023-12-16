<?php
    require_once 'connection.php';

    Class tblSubjects extends connection{

        public function getAll(){
            $sql = "";
            
            $sql = "SELECT * FROM subject ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $response =  $stmt->fetchAll();

            return $response;
        }
        public function get($id){
            $sql = "";
            
            $sql = "SELECT * FROM subject WHERE id=:id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["id"=>$id]);
            $response =  $stmt->fetch();

            return $response;
        }

        public function insert($subject_id,$name,$credit){

            $sql = "INSERT INTO subject (subject_id,name,credit) ";
            $sql .= "VALUES(:subject_id,:name,:credit);";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["subject_id"=>$subject_id,"name"=>$name,"credit"=>$credit]);

            $response =  $this->conn->lastInsertId();

            return $response;
        }

        public function update($id,$name,$credit){

            $sql = "UPDATE subject SET ";
            $sql .= " name=:name ,";
            $sql .= " credit=:credit ";
            $sql .= " WHERE id=:id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["id"=>$id,"name"=>$name,"credit"=>$credit]);

            return $id;
        }

        public function delete($id){
            $sql = "DELETE FROM subject ";
            $sql .= " WHERE id=:id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["id"=>$id]);

            return $id;
        }
    }
?>