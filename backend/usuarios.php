<?php

namespace App\usuarios;
require "../vendor/autoload.php";

use App\Controller\UserController;
use App\Model\Usuario;

$usuario = new Usuario();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: * ' );
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, no-store, must-revalidate');

$body = json_decode(file_get_contents('php://input'), true);

$id = isset($_GET['id']) ? $_GET['id'] : '';
switch($_SERVER["REQUEST_METHOD"]){
    case "POST";
        $usuario->setEmail($body['email']);
        $usuario->setSenha($body['senha']);
        
        $usuariosController = new UserController($usuario);
        $resultado = $usuariosController->adicionarUsuario();
        echo json_encode(['status' => $resultado]);
    break;
    case "GET":
        $usuariosController = new UserController($usuario);
        if(!isset($_GET['id'])){
            $resultado = $usuariosController->listarUsuarios();
            if(!$resultado){
                echo json_encode(["status" => false, "usuarios" => $resultado,"mensagem"=>"nenhum resultado encontrado"]);
                exit;
            }else{
                echo json_encode(["status" => true, "usuarios" => $resultado]);
                exit;
            }
        }else{
            $resultado = $usuariosController->buscarPorEmail($id);
            if(!$resultado){
                echo json_encode(["status" => false, "usuarios" => $resultado,"mensagem"=>"nenhum resultado encontrado"]);
                exit;
            }else{
                echo json_encode(["status" => true, "usuarios" => $resultado[0]]);
                exit;
            }
        }
    
    break;  
}