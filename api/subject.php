<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Header: Content-Type,Access-Control-Allow-Headers,Authorization, X-Requested-With");
    header("Content-Type: application/json");

    require_once 'class/tblSubjects.php';

    $api = $_SERVER["REQUEST_METHOD"];
    $tblSubject = new tblSubjects();

    switch($api){

        case "GET":
            $id = intval($_GET["id"] ?? null) ;
            if($id == null){
                $data = $tblSubject->getAll();
            }else{
                $data = $tblSubject->get($id);
            }

            echo json_encode([ 'status'=> true ,
                                'message'=> 'found!!',
                                'data'=> $data]);
            
            break;
        case "POST":

            $data = json_decode(file_get_contents('php://input'));

            $subject_id = ($data->subject_id  ?? $_POST["subject_id"]) ;
            $name = ( $data->name ?? $_POST["name"]) ;
            $credit = ($data->credit ?? $_POST["credit"]) ;

            $data = $tblSubject->insert($subject_id,$name,$credit);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'insert!!',
                                'data'=> ['id'=> $data]]);

            break;
        case "PUT":

            $data = json_decode(file_get_contents('php://input'));

            $id = intval($_GET["id"] ?? null) ;

            $name = ( $data->name ?? $_POST["name"]) ;
            $credit = ($data->credit ?? $_POST["credit"]) ;

            $data = $tblSubject->update($id,$name,$credit);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'update!!',
                                'data'=> ['id'=> $data]]);



            break;
        case "DELETE" :

            $id = intval($_GET["id"] ?? null) ;

            $data = $tblSubject->delete($id);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'delete!!',
                                'data'=> ['id'=> $data]]);

            break;
    }
?>