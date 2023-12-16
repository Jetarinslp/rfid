<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Header: Content-Type,Access-Control-Allow-Headers,Authorization, X-Requested-With");
    header("Content-Type: application/json");

    require_once 'class/tblYears.php';

    $api = $_SERVER["REQUEST_METHOD"];
    $tblYear = new tblYears();

    switch($api){

        case "GET":
            $id = intval($_GET["id"] ?? null) ;
            if($id == null){
                $data = $tblYear->getAll();
            }else{
                $data = $tblYear->get($id);
            }

            echo json_encode([ 'status'=> true ,
                                'message'=> 'found!!',
                                'data'=> $data]);
            
            break;
        case "POST":

            $data = json_decode(file_get_contents('php://input'));

            $name = ( $data->name ?? $_POST["name"]) ;
            $description = ($data->description ?? $_POST["description"]) ;

            $data = $tblYear->insert($name,$description);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'insert!!',
                                'data'=> ['id'=> $data]]);

            break;
        case "PUT":

            $data = json_decode(file_get_contents('php://input'));

            $id = intval($_GET["id"] ?? null) ;

            $name = ( $data->name ?? $_POST["name"]) ;
            $description = ($data->description ?? $_POST["description"]) ;

            $data = $tblYear->update($id,$name,$description);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'update!!',
                                'data'=> ['id'=> $data]]);



            break;
        case "DELETE" :

            $id = intval($_GET["id"] ?? null) ;

            $data = $tblYear->delete($id);
            
            echo json_encode([ 'status'=> true ,
                                'message'=> 'delete!!',
                                'data'=> ['id'=> $data]]);

            break;
    }
?>