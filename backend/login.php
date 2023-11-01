<?php

namespace App\login;
require "../vendor/autoload.php";

use App\Controller\UserController;
use App\Usuario\Usuario;

$usuario = new Usuario();
$user = new UserController();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: * ' );
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, no-store, must-revalidate');

$body = json_decode(file_get_contents('php://input'), true);
switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
        
        $resultado = $user->login($body['email']);
            if (!$resultado) {
                echo json_encode(['status' => false, 'message' => 'Usuário não encontrado.']);
            }
            if (!password_verify($senha, $this->cripto->show($resultado[0]['senha']))) {
                echo json_encode(['status' => false, 'message' => 'Senha incorreta.']);
            }
            $key = "9b426114868f4e2179612445148c4985429e5138758ffeed5eeac1d1976e7443";
            $algoritimo='HS256';
                $payload = [
                    "iss" => "localhost",
                    "aud" => "localhost",
                    "iat" => time(),
                    "exp" => time() + (60 * $checado),  
                    "sub" => $this->usuario->getEmail()
                ];
                
                $jwt = JWT::encode($payload, $key,$algoritimo);
               
                echo json_encode(['status' => true, 'message' => 'Login bem-sucedido!','token'=>$jwt]);
                exit;
        break;
        case "GET":
            $headers = getallheaders();
            $token = $headers['Authorization'] ?? null;
            $usuariosController = new UserController($usuario);
            //$validationResponse = $usuariosController->validarToken($token);
            if ($token === null || !$validationResponse['status']) {
                echo json_encode(['status' => false, 'message' => $validationResponse['message']]);
                exit;
            }
            echo json_encode(['status' => true, 'message' => 'Token válido']);
            exit;
            break; 
}