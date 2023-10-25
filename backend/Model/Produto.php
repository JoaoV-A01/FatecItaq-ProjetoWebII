<?php
namespace App\Model;
class Produto {
    private int $id;
    private string $nome;
    private float $preco;
    private int $quantidade;
    public $conn;

    public function __construct() {
        $this->conn = new Model();
        $this->conn->createTableFromModel($this);
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
    public function getpreco() {
        return $this->nome;
    }
    public function setpreco($preco) {
        $this->preco = $preco;
    }
    public function setquantidade($quantidade) {
        $this->quantidade = $quantidade;
    }
    public function getquantidade() {
        return $this->quantidade;
    }

    

    public function getType() {
        return 'User';
    }

    public function toArray() {
        return ['id' => $this->getId(), 'nome' => $this->getNome(), 'type' => $this->getType()];
    }
}
