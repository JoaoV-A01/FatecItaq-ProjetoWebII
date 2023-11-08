<?php

namespace App\login;

require "../vendor/autoload.php";

use App\Controller\UserController;
use App\Usuario\Usuario;

$usuario = new Usuario();
$users = new UserController();

$body = json_decode(file_get_contents('php://input'), true);
switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
        $resultado = $users->login($body['senha'], $body['email']);
        echo json_encode($resultado);
        /*
        if (isset($body['email'])) {
            $usuario->setEmail($body['email']);
            $email=$body['email'];
            $senha=$body['senha'];
            $userController = new UserController($usuario);
            $resultado = $usuariosController->login($senha,$email);
            if(!$resultado['status']){
                echo json_encode(['status' => $resultado['status'], 'message' => $resultado['message']]);
               exit;
            }
            echo json_encode(['status' => $resultado['status'], 'message' => $resultado['message'],'token'=>$resultado['token']]);
        }
        */
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