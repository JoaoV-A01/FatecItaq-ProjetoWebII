<?php

namespace App\Controller; 

use App\Model\Model;
use App\Endereco\Endereco;
use App\Controller\EnderecoController;
use App\Usuario\Usuario;
//use App\Database\Crud;
//use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
//use stdClass;
//use App\Cryptonita\Crypto;
class UserController {

    private $db;
    private $usuario;
    private $endereco;
    //private $cripto;
    
    public function __construct() {
        $this->db = new Model();
        $this->usuario = new Usuario();
        $this->endereco = new Endereco();
        //$this->cripto=new Crypto();
    }
    /*
    public function validarToken($token){
        
        $key = TOKEN;
        $algoritimo = 'HS256';
        try {
            $decoded = JWT::decode($token, new Key($key, $algoritimo));
            return ['status' => true, 'message' => 'Token válido!', 'data' => $decoded];
        } catch(Exception $e) {
            return ['status' => false, 'message' => 'Token inválido! Motivo: ' . $e->getMessage()];
        }
    }
    */
    /*
    public function login($senha,$lembrar) {
        $condicoes = ['email' => $this->usuario->getEmail()];
        $resultado = $this->select($this->usuario, $condicoes);
        $checado=$lembrar? 60*12 : 3;
        if (!$resultado) {
            return ['status' => false, 'message' => 'Usuário não encontrado.'];
        }
        if (!password_verify($senha, $this->cripto->show($resultado[0]['senha']))) {
            return ['status' => false, 'message' => 'Senha incorreta.'];
        }
        $key = TOKEN;
        $algoritimo='HS256';
            $payload = [
                "iss" => "localhost",
                "aud" => "localhost",
                "iat" => time(),
                "exp" => time() + (60 * $checado),  
                "sub" => $this->usuario->getEmail()
            ];
            
            $jwt = JWT::encode($payload, $key,$algoritimo);
           
        return ['status' => true, 'message' => 'Login bem-sucedido!','token'=>$jwt];
    }
    */
    public function select(){
        $user = $this->db->select('usuarios');
        
        return  $user;
    }
    public function selectId($id){
        $user = $this->db->select('usuarios',['id'=>$id]);
        
        return  $user;
    }
    public function login($email){
        $user = $this->db->select('usuarios',['email'=>$email]);
        
        return  $user;
    }
    public function selectIdade(){
        $user = $this->db->select('idades');
        
        return  $user;
    }
    public function insert($data){
        $this->usuario->setNome($data['nome']);
        $this->usuario->setEmail($data['email']);
        $this->usuario->setSenha($data['senha']);
        $this->usuario->setDataNasc($data['datanasc']);
        if($this->db->insert('usuarios', [
            'nome'=> $this->usuario->getNome(),
            'email'=> $this->usuario->getEmail(),
            'senha'=> $this->usuario->getSenha(),
            'datanasc'=> $this->usuario->getDataNasc(),
        ])){
            $this->endereco->setCep($data['cep']);
            $this->endereco->setRua($data['rua']);
            $this->endereco->setBairro($data['bairro']);
            $this->endereco->setCidade($data['cidade']);
            $this->endereco->setUf($data['uf']);
            $this->endereco->setIduser($this->db->getLastInsertId());   
            $enderecocontroller = new EnderecoController($this->endereco);  
            if($enderecocontroller->insert()){
                return true;
            }
        }
        return false;
    }
    public function update($newData,$condition){
        if($this->db->update('usuarios', $newData, ['id'=>$condition])){
            return true;
        }
        return false;
    }
    public function delete( $conditions){
        if($this->db->delete('usuarios', ['id'=>$conditions])){
            return true;
        }
        return false;
        
    }

}
