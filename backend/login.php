<?php

namespace App\login;
require "../vendor/autoload.php";

use App\Controller\UserController;
use App\Usuario\Usuario;

$usuario = new Usuario();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: * ' );
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, no-store, must-revalidate');

$body = json_decode(file_get_contents('php://input'), true);
switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
        $usuario->setEmail($body['email']);
        $senha=$body['senha'];
        $userController = new UserController($usuario);
        $resultado = $userController->login($senha);
        echo json_encode($resultado);
        break;
        case "GET":
            $headers = getallheaders();
            $token = $headers['Authorization'] ?? null;
            $userController = new UserController($usuario);
            $validationResponse = $usuariosController->validarToken($token);
            if ($token === null || !$validationResponse['status']) {
                echo json_encode(['status' => false, 'message' => $validationResponse['message']]);
                exit;
            }
            echo json_encode($validationResponse);
            break; 
}