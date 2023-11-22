<?php
namespace App\Model;

use Exception;
use PDO;
use PDOException;
use App\Cryptonita\Crypto;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

 class Model {

/*
//banco de dados local
*/
 private $host = "localhost";
 private $db_name = "bd_webii";
 private $username = "root";
 private $password = "";
 private $conn;
 private $db_type = "mysql"; // Opções: "mysql", "pgsql", "sqlite", "mssql"
 

 /*
 //banco de dados online
 private $host = "localhost";
 private $db_name = "id21521132_bdfatecweb";
 private $username = "id21521132_jv";
 private $password = "BDFatec321@";
 private $conn;
 private $db_type = "mysql"; // Opções: "mysql", "pgsql", "sqlite", "mssql"
 */
 
 private $cripto;
 public function __construct() {
     $this->connect();
     //$this->criarTabelaEndereco();
     //$this->criarTabelaVendas();
     $this->criarViewProdutosPorUsuario();
     //parent::__construct();
     $this->cripto=new Crypto();
 }

 private function connect() {
  $this->conn = null;

  try {
    switch ($this->db_type) {
        case "mysql":
          $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            break;
        case "pgsql":
            $dsn = "pgsql:host=" . $this->host . ";dbname=" . $this->db_name;
            break;
        case "sqlite":
            $dsn = "sqlite:" . "sqlite/test_drive.db";
            $filepath =  "sqlite/test_drive.db";
            if (!file_exists($filepath)) {
                die("Arquivo não encontrado: $filepath");
            }
            break;
        case "mssql":
           $dsn = "sqlsrv:Server=" . $this->host . ";Database=" . $this->db_name;
           break;
        default:
            throw new Exception("Database type not supported.");
      }
      if ($this->db_type == "sqlite") {
        $this->conn = new PDO($dsn);
    } else {
        $this->conn = new PDO($dsn, $this->username, $this->password);
    }
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
        echo "Connection error: " . $exception->getMessage();
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
}

public function getLastInsertId() {
    return $this->conn->lastInsertId();
}
public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(function($item) {
            return ":$item"; 
        }, array_keys($data)));
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
}

public function select($table, $conditions = []) { //$object troca por $table
        //$reflectionClass = new \ReflectionClass($object);
        //$table=$reflectionClass->getShortName();
        $query = "SELECT * FROM $table";
        if (!empty($conditions)) {
            $conditionsStr = implode(" AND ", array_map(function($item) {
            return "$item = :$item";
            }, array_keys($conditions)));
            $query .= " WHERE $conditionsStr";
        }
        $stmt = $this->conn->prepare($query);
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function update($table, $data, $conditions) {
        //$reflectionClass = new \ReflectionClass($object);
        //$properties = $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        $dataStr = implode(", ", array_map(function($item) {
            return "$item = :$item"; 
        }, array_keys($data)));
        $conditionsStr = implode(" AND ", array_map(function($item) { 
            return "$item = :condition_$item"; 
        }, array_keys($conditions)));
        $query = "UPDATE $table SET $dataStr WHERE $conditionsStr";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":condition_$key", $value);
        }
   return $stmt->execute();
}

public function delete($table, $conditions) {
        //$reflectionClass = new \ReflectionClass($object);
        //$table = $reflectionClass->getShortName();
        $conditionsStr = implode(" AND ", array_map(function($item) {
            return "$item = :$item"; 
        }, array_keys($conditions)));
        $query = "DELETE FROM $table WHERE $conditionsStr";
        $stmt = $this->conn->prepare($query);
        foreach ($conditions as $key => $value) {
        $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }
    /*
    public function deleteWithCustomCondition($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
    */
    public function CallInsert($table, $data) {
        $placeholders = implode(", ", array_map(function($item) {
            return ":$item"; 
        }, array_keys($data)));
        $query = "CALL Insert{$table}({$placeholders})";
    
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }
    public function callDelete($table, $conditions) {
        $conditionPlaceholders = implode(", ", array_map(function($item) {
            return ":$item";
        }, array_keys($conditions)));
        $query = "CALL Delete{$table}({$conditionPlaceholders})";
        $stmt = $this->conn->prepare($query);
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }
    public function deleteWithCustomCondition($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
    /*
    public function criarTabelaEndereco(){
        $sql = "
        CREATE TABLE IF NOT EXISTS endereco (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            cep TEXT NOT NULL,
            rua TEXT NOT NULL,
            bairro TEXT NOT NULL,
            cidade TEXT NOT NULL,
            uf TEXT NOT NULL,
            idUser INTEGER,
            FOREIGN KEY (idUser)
            REFERENCES users (id) ON DELETE CASCADE
        )";
        $this->conn->exec($sql);    
    }
    
    public function criarTabelaVendas(){
        $sql = "
        CREATE TABLE IF NOT EXISTS vendas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            id_usuario INTEGER,
            id_produto INTEGER,
            data_criacao DATETIME,
            FOREIGN KEY (id_usuario) REFERENCES users(id),
            FOREIGN KEY (id_produto) REFERENCES produtos(id)
        )";
        $this->conn->exec($sql);
    }
    */

    public function criarViewProdutosPorUsuario(){
        $sql = "
        CREATE VIEW IF NOT EXISTS produtos_por_usuario AS
        SELECT u.id, u.nome, COUNT(v.id_produto) as quantidade_produtos
        FROM usuarios u
        LEFT JOIN vendas v ON u.id = v.id_usuario
        GROUP BY u.id";
        $this->conn->exec($sql);
    }
    //GERENCIAMENTO DE PERFIL
    public function selectPermissoesPorPerfil($perfilId) {
        $stmt = $this->conn->prepare("CALL GetPermissoesPorPerfil(:perfilId)");
        $stmt->bindValue(":perfilId", $perfilId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarTodosOsPerfis()
    {
        $query = "SELECT id, nome FROM perfil";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadPermissao($permissao)
    {
        $query = "
            INSERT INTO permissoes (nome) VALUES (:nome)
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $permissao);
        return $stmt->execute();
    }
    public function associar($perfilId, $permissaoId)
    {
        $query = "
            INSERT INTO perfil_permissoes (perfilid, permissao_id) VALUES (:perfilid, :permissao_id)
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":perfilid", $perfilId);
        $stmt->bindParam(":permissao_id", $permissaoId);
        return $stmt->execute();
    }
    public function listarTodasPermissoes()
    {
        $query = "SELECT id, nome FROM permissoes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function desassociar($perfilId, $permissaoId)
    {
        $query = "
            DELETE FROM perfil_permissoes WHERE perfilid = :perfilid AND permissao_id = :permissao_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":perfilid", $perfilId);
        $stmt->bindParam(":permissao_id", $permissaoId);
        return $stmt->execute();
    }
    public function listarPermissao($permissao)
    {
        $query = "
        SELECT id FROM permissoes where nome=:permissao
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":permissao", $permissao);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarPerfisPorPermissao($permissaoId)
    {
        $query = "
            SELECT perfil.id, perfil.nome 
            FROM perfil_permissoes
            JOIN perfil ON perfil.id = perfil_permissoes.perfilid
            WHERE perfil_permissoes.permissao_id = :permissao_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":permissao_id", $permissaoId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}