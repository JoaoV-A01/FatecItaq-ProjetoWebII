<?php

namespace App\Controller;

use App\Model\Model;

class VendaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function insert($data)
    {
        $idusuario = $data['idusuario'];
        $idproduto = $data['idproduto'];
        $data_criacao = date('Y-m-d H:i:s');

        try {
            $this->model->insert('vendas', ['id' => $idusuario, 'id' => $idproduto, 'data_criacao' => $data_criacao]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function selectprodId()
    {
        $user = $this->model->select('produtos_por_usuario');
        return $user;
    }
}
