<?php
namespace App\Model;

use Exception;
use PDO;
use PDOException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

 class Model {
 private $host = "localhost";
 private $db_name = "test_drive";
 private $username = "root";
 private $password = "root123";
 private $conn;
 private $db_type = "sqlite"; // Opções: "mysql", "pgsql", "sqlite", "mssql"

 public function __construct() {
     $this->connect();
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

public function select($table, $conditions = []) {
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
    public function deleteWithCustomCondition($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
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
    
    private function mapPhpTypeToSqlType($type) {
        switch ($type) {
            case 'int':
                return "INT";
            case 'float':
                return "FLOAT";
            case 'DateTime':
                return "DATETIME";
            case 'string':
                return "VARCHAR(255)";
            case 'bool':
                return "BOOLEAN";
            default:
                throw new Exception("Tipo PHP não mapeado: $type");
            }
        }
        
        public function createTableFromModel($model) {
            try{
                $reflection = new ReflectionClass($model);
                $properties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
                $columns = [];
                $columnNames = [];  
            foreach ($properties as $property) {
                $columnName = $property->getName();
                $type = $property->getType()->getName();
                if (!$type) {
                    continue; 
                }
                $sqlType = $this->mapPhpTypeToSqlType($type);
                $columns[] = "{$columnName} {$sqlType}";
                $columnNames[] = $columnName;
            }
        
            $tableName = str_replace('App','',str_replace('Model','',str_replace('\\','',$reflection->getName())));
            
            $columnsSql = implode(', ', $columns);
            $createTableSql = "CREATE TABLE IF NOT EXISTS {$tableName} (".str_replace('id INT,','id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,',$columnsSql).")";
            $stmt = $this->conn->prepare($createTableSql);
            $stmt->execute();
        
            $placeholders = array_map(function($colName) { return ":{$colName}"; }, $columnNames);
    
            $this->createInsertProcedure($tableName, $columnNames, $columns);
            $this->createUpdateProcedure($tableName, $columns);
            $this->createDeleteProcedure($tableName);
            $this->createSelectAllProcedure($tableName);
            $this->createSelectByIdProcedure($tableName);
        } catch (ReflectionException $e) {
            echo "Erro de Reflexão: " . $e->getMessage();
        } catch (PDOException $e) {
            echo "Erro de Banco de Dados: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
        }
        
        private function createInsertProcedure($tableName, $columnNames, $placeholders) {
            $columnNamesWithoutId = array_filter($columnNames, function($colName) {
                 return $colName ; 
                });
            $columnsStr = implode(', ', $columnNamesWithoutId);
            $placeholdersStr = implode(', p_', $placeholders);
            $dropProcedureSQL = "DROP PROCEDURE IF EXISTS Insert{$tableName}";
            $this->conn->exec($dropProcedureSQL);
        
            $sql = "
                CREATE PROCEDURE Insert{$tableName}(".str_replace('id INT,','',str_replace(',',', IN',$placeholdersStr)).")
                BEGIN
                    INSERT INTO {$tableName} (".str_replace('id,','',$columnsStr).") VALUES (".str_replace('id INT,','',str_replace('INT','',str_replace('VARCHAR(255)','',str_replace('DATETIME','',str_replace('FLOAT','',str_replace('BOOLEAN','',$placeholdersStr)))))).");
                END;
            ";
            $this->conn->exec($sql);
        }
        
        private function createUpdateProcedure($tableName, $columns) {
            $columnsWithoutId = array_filter($columns, function($col) { return !str_starts_with($col, "id"); });
            $updateStatements = array_map(function($col) { 
                $colName = explode(' ', $col)[0];
                return "{$colName} = p_{$colName}"; 
            }, $columnsWithoutId);
            $updateStr = implode(', ', $updateStatements);
            $dropProcedureSQL = "DROP PROCEDURE IF EXISTS Update{$tableName}";
            $this->conn->exec($dropProcedureSQL);
            $params = implode(', ', array_map(function($col) {
                return "p_{$col}";
            }, $columnsWithoutId));
            $sql = "
                CREATE PROCEDURE Update{$tableName}(IN id INT, {$params})
                BEGIN
                    UPDATE {$tableName} SET {$updateStr} WHERE id = id;
                END;
            ";
            $this->conn->exec($sql);
        }
        
        
        private function createDeleteProcedure($tableName) {
            $dropProcedureSQL = "DROP PROCEDURE IF EXISTS Delete{$tableName}";
            $this->conn->exec($dropProcedureSQL);
            $sql = "
                CREATE PROCEDURE Delete{$tableName}(IN idx INT)
                BEGIN
                    DELETE FROM {$tableName} WHERE id = idx;
                END;
            ";
        
            $this->conn->exec($sql);
        }
        
        private function createSelectAllProcedure($tableName) {
            $dropProcedureSQL = "DROP PROCEDURE IF EXISTS SelectAll{$tableName}";
            $this->conn->exec($dropProcedureSQL);
            $sql = "
                CREATE PROCEDURE SelectAll{$tableName}()
                BEGIN
                    SELECT * FROM {$tableName};
                END;
            ";
        
            $this->conn->exec($sql);
        }
        
        private function createSelectByIdProcedure($tableName) {
            $dropProcedureSQL = "DROP PROCEDURE IF EXISTS SelectById{$tableName}";
            $this->conn->exec($dropProcedureSQL);
            $sql = "
                CREATE PROCEDURE SelectById{$tableName}(IN idx INT)
                BEGIN
                    SELECT * FROM {$tableName} WHERE id = idx;
                END;
            ";
        
            $this->conn->exec($sql);
        }
}