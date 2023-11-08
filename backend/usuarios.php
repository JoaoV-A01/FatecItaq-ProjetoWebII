<?php

namespace App\usuarios;
require "../vendor/autoload.php"; 

use App\Controller\UserController;

$users = new UserController();

$body = json_decode(file_get_contents('php://input'), true);
$id=isset($_GET['id'])?$_GET['id']:'';
switch($_SERVER["REQUEST_METHOD"]){
    case "POST";
        $resultado = $users->insert($body);
        echo json_encode(['status'=>$resultado]);
    break;
    case "GET";
        if(!isset($_GET['id'])){
            $resultado = $users->select();
            if(!is_array($resultado)){
                echo json_encode(["status"=>false]);
                exit;
            }
            echo json_encode(["status"=>true,"usuarios"=>$resultado]);
        }else{
            $resultado = $users->selectId($id);
            echo json_encode(["status"=>true,"usuarios"=>$resultado[0]]);
        }
       
    break;
    case "PUT";
        $resultado = $users->update($body,intval($_GET['id']));
        echo json_encode(['status'=>$resultado]);
    break;
    case "DELETE";
        $resultado = $users->delete(intval($_GET['id']));
        echo json_encode(['status'=>$resultado]);
    break;  
}
