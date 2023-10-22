<?php

namespace App\prods;
require "../vendor/autoload.php";

use App\Controller\ProdutoController;

$prods = new ProdutoController();

$body = json_decode(file_get_contents('php://input'), true);
$id=isset($_GET['id'])?$_GET['id']:'';
switch($_SERVER["REQUEST_METHOD"]){
    case "POST";
        $resultado = $prods->insert($body);
        echo json_encode(['status'=>$resultado]);
    break;
    case "GET";
        if(!isset($_GET['id'])){
            $resultado = $prods->select();
            if(!is_array($resultado)){
                echo json_encode(["status"=>false]);
                exit;
            }
            echo json_encode(["status"=>true,"produtos"=>$resultado]);
        }else{
            $resultado = $prods->selectId($id);
            echo json_encode(["status"=>true,"produto"=>$resultado[0]]);
        }
       
    break;
    case "PUT";
        $resultado = $prods->update($body,intval($_GET['id']));
        echo json_encode(['status'=>$resultado]);
    break;
    case "DELETE";
        $resultado = $prods->delete(intval($_GET['id']));
        echo json_encode(['status'=>$resultado]);
    break;  
}