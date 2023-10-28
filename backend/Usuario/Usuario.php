<?php
namespace App\Usuario;
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $datanasc;
    private $senha;

    public function __construct() {
      
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function getDataNasc() {
        return $this->datanasc;
    }
    public function setDataNasc($datanasc) {
        $this->datanasc = $datanasc;
    }
    public function setSenha($senha) {
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }
    public function getSenha() {
        return $this->senha;
    }

    public function getType() {
        return 'User';
    }

    public function toArray() {
        return ['id' => $this->getId(), 'nome' => $this->getNome(), 'type' => $this->getType()];
    }
}
