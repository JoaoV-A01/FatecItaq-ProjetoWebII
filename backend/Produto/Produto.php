<?php
namespace App\Produto;
class Produto {
    private $id;
    private $nome;
    private $preco;
    private $quantidade;

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
    public function getPreco() {
        return $this->preco;
    }
    public function setPreco($preco) {
        $this->preco = $preco;
    }
    public function setQnt($quantidade) {
        $this->quantidade = $quantidade;
    }
    public function getQnt() {
        return $this->quantidade;
    }

    

    public function getType() {
        return 'User';
    }

    public function toArray() {
        return ['id' => $this->getId(), 'nome' => $this->getNome(), 'type' => $this->getType()];
    }
}
