<?php

namespace App\endereco;
require "../vendor/autoload.php";

use App\Controller\EnderecoController;
use App\Endereco\Endereco;

$ende = new Endereco();
$body = json_decode(file_get_contents('php://input'), true);
$id=isset($_GET['id'])?$_GET['id']:'';
switch($_SERVER["REQUEST_METHOD"]){
    case "POST";
        $ende->setCep($body['cep']);
        $ende->setRua($body['rua']);
        $ende->setBairro($body['bairro']);
        $ende->setCidade($body['cidade']);
        $ende->setUf($body['uf']);
        $ende->setIduser($body['iduser']);   
        $enderecocontroller = new EnderecoController($ende); 
        $resultado = $enderecocontroller->insert();    
        echo json_encode(['status'=>$resultado]);
    break;
    case "GET";
        if(!isset($_GET['id'])){
            $enderecocontroller = new EnderecoController($ende); 
            $resultado = $enderecocontroller->select();
            echo json_encode(["enderecos"=>$resultado]);
        }else{
            $enderecocontroller = new EnderecoController($ende); 
            $resultado = $enderecocontroller->selectId($id);
            echo json_encode(["status"=>true,"endereco"=>$resultado[0]]);
        }
       
    break;
    case "PUT";
        $enderecocontroller = new EnderecoController($ende); 
        $resultado = $enderecocontroller->update($body,intval($_GET['id']));
        echo json_encode(['status'=>$resultado]);
    break;
    case "DELETE";
        $enderecocontroller = new EnderecoController($ende); 
        $resultado = $enderecocontroller->delete(intval($_GET['id']));
        echo json_encode(['status'=>$resultado]);
    break;  
}