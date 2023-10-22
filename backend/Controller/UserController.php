<?php

namespace App\Controller;

use App\Model\Model;
use App\Usuario\Usuario;
class UserController {

    private $db;
    private $usuario;
    
    public function __construct() {
        $this->db = new Model();
        $this->usuario = new Usuario();
    }
    public function select(){
        $user = $this->db->select('users');
        
        return  $user;
    }
    public function selectId($id){
        $user = $this->db->select('users',['id'=>$id]);
        
        return  $user;
    }
    public function insert($data){
        $this->usuario->setNome($data['nome']);
        $this->usuario->setEmail($data['email']);
        $this->usuario->setSenha($data['senha']);
        if($this->db->insert('users', [
            'nome'=> $this->usuario->getNome(),
            'email'=> $this->usuario->getEmail(),
            'senha'=> $this->usuario->getSenha(),
        ])){
            return true;
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
