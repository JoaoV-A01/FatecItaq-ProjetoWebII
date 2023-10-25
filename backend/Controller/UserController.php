<?php

namespace App\Controller;

use App\Model\Model;
use App\Endereco\Endereco;
use App\Controller\EnderecoController;
use App\Usuario\Usuario;
class UserController {

    private $db;
    private $usuario;
    private $endereco;
    
    public function __construct() {
        $this->db = new Model();
        $this->usuario = new Usuario();
        $this->endereco = new Endereco();
    }
    public function select(){
        $user = $this->db->select('users');
        
        return  $user;
    }
    public function selectId($id){
        $user = $this->db->select('users',['id'=>$id]);
        
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
        $this->usuario->setDataNascimento($data['datanasc']);
        if($this->db->insert('users', [
            'nome'=> $this->usuario->getNome(),
            'email'=> $this->usuario->getEmail(),
            'senha'=> $this->usuario->getSenha(),
            'datanasc'=> $this->usuario->getDataNascimento(),
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
        if($this->db->update('users', $newData, ['id'=>$condition])){
            return true;
        }
        return false;
    }
    public function delete( $conditions){
        if($this->db->delete('users', ['id'=>$conditions])){
            return true;
        }
        return false;
        
    }
}
