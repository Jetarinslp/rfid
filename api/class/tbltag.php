<?php
    require_once 'connection.php';

    Class tbltag extends connection{

        public function insert($tag_id,$name){

            $sql = "INSERT INTO tag (tag_id,name) ";
            $sql .= "VALUES(:tag_id,:name);";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["tag_id"=>$tag_id,"name"=>$name]);

            $response =  $this->conn->lastInsertId();

            return $response;
        }

        public function update($tag_id, $name) {
            if (!$tag_id) {
                return null;
            }
        
            $sql = "UPDATE tag SET name=:name WHERE tag_id=:tag_id";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":tag_id", $tag_id, PDO::PARAM_INT);
            $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        
            $stmt->execute();
        
            return $tag_id;
        }
        
        public function delete($tag_id){
            $sql = "DELETE FROM tag ";
            $sql .= " WHERE tag_id=:tag_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["tag_id"=>$tag_id]);

            return $tag_id;
        }
    }
?>