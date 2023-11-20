<?php

namespace App\Controller;

use App\Model\Model;
use App\Database\Crud;
use App\Model\Usuario;
use App\Endereco\Endereco;
use App\Controller\EnderecoController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use stdClass;
use App\Cryptonita\Crypto;
class UserController { //extends Crud arquivo com algumas funções com modificações  

    private $db;
    private $usuario;
    private $endereco;
    private $cripto;
    
    public function __construct($usuarios) { //$usuarios
        $this->db = new Model();
        $this->usuario = new Usuario();
        $this->endereco = new Endereco();
        $this->cripto=new Crypto();
        //parent::__construct();
        $this->usuario=$usuarios;
    }
    public function select(){
        $user = $this->db->select('usuarios');
        
        return  $user;
    }
    public function selectId($id){
        $user = $this->db->select('usuarios',['id'=>$id]);
        
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
    public function validarToken($token){
        $key = TOKEN; //01101010
        $algoritimo = 'HS256';
        try {
            $decoded = JWT::decode($token, new Key($key, $algoritimo));
            $permissoes = $decoded->telas;
            return ['status' => true, 'message' => 'Token válido!', 'tela'=>$permissoes];
        } catch(Exception $e) {
            return ['status' => false, 'message' => 'Token inválido! Motivo: ' . $e->getMessage()];
        }
    }
    public function login($senha,$lembrar) {
        $condicoes = ['email' => $this->usuario->getEmail()];
        $resultado = $this->select($this->usuario, $condicoes); //$this->usuario
        //$resultado = $this->db->select('usuarios', $condicoes);
        $checado=$lembrar? 60*12 : 3;
        if (!$resultado) {
            return ['status' => false, 'message' => 'Usuário não encontrado.'];
        }
        if (!password_verify($senha, $this->cripto->show($resultado[0]['senha']))) {
            return ['status' => false, 'message' => 'Senha incorreta.'];
        }
        $permissoes = $this->selectPermissoesPorPerfil($resultado[0]['perfilid']);
        $key = TOKEN; //01101010
        $algoritimo='HS256';
            $payload = [
                "iss" => "localhost",
                "aud" => "localhost",
                "iat" => time(),
                "exp" => time() + (60 * $checado),  
                "sub" => $this->usuario->getEmail(),
                'telas'=>$permissoes
            ];
            
            $jwt = JWT::encode($payload, $key,$algoritimo);
        return ['status' => true, 'message' => 'Login bem-sucedido!','token'=>$jwt,'telas'=>$permissoes];
    }
    public function adicionarUsuario(){
        return $this->insert($this->usuario);
    }
    
    public function listarUsuario(){
        return $this->select($this->usuario);
    }
    public function listarUsuarioDescriptografado(){
        $resultado=$this->select($this->usuario);
        $retorno[]=[
            'id' => $resultado[0]['id'],
            'nome' => $this->cripto->show($resultado[0]['nome']),
            'email' => $this->cripto->show($resultado[0]['email']),
            'criado' => $resultado[0]['criado'],
        ];
             
        return $retorno;
    }
    
    public function buscarPorEmail(string $email){
        $condicoes = ['email' => $email];
        $resultados = $this->select($this->usuario, $condicoes);
        return count($resultados) > 0 ? $resultados[0] : null;
    }
    
    public function removerUsuario(){
        $condicoes = ['email' => $this->usuario->getEmail()];
        return $this->delete($this->usuario, $condicoes);
    }
}