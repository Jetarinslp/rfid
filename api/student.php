<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Header: Content-Type,Access-Control-Allow-Headers,Authorization, X-Requested-With");
    header("Content-Type: application/json");

    require_once 'class/tblStudents.php';

    $api = $_SERVER["REQUEST_METHOD"];
    $tblSutent = new tblStudents();

    switch($api){

        case "GET":
            $id = intval($_GET["id"] ?? null) ;
            if($id == null){
                $data = $tblSutent->getAll();
            }else{
                $data = $tblSutent->get($id);
            }

            echo json_encode([ 'status'=> true ,
                                'message'=> 'found!!',
                                'data'=> $data]);
            
            break;
        case "POST":

            $data = json_decode(file_get_contents('php://input'));

            $student_id = ($data->student_id  ?? $_POST["student_id"]) ;
            $name = ( $data->name ?? $_POST["name"]) ;
            $surname = ($data->surname ?? $_POST["surname"]) ;

            $data = $tblSutent->insert($student_id,$name,$surname);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'insert!!',
                                'data'=> ['id'=> $data]]);

            break;
        case "PUT":

            $data = json_decode(file_get_contents('php://input'));

            $id = intval($_GET["id"] ?? null) ;

            $name = ( $data->name ?? $_POST["name"]) ;
            $surname = ($data->surname ?? $_POST["surname"]) ;

            $data = $tblSutent->update($id,$name,$surname);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'update!!',
                                'data'=> ['id'=> $data]]);



            break;
        case "DELETE" :

            $id = intval($_GET["id"] ?? null) ;

            $data = $tblSutent->delete($id);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'delete!!',
                                'data'=> ['id'=> $data]]);

            break;
    }
?>