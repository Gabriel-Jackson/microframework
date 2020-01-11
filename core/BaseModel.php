<?php
namespace Core;

use \PDO;

abstract class BaseModel
{
    private $pdo;
    protected $table;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function All(){
    $query = "SELECT * FROM {$this->table}";

    $stat = $this->pdo->prepare($query);

    $stat->execute();
    $result = $stat->fetchAll();
    $stat->closeCursor();

    return $result;
    }

    public function findByID($id){
        $query = "SELECT * FROM {$this->table} where id=:id";
        $stat = $this->pdo->prepare($query);
        $stat->bindValue(':id',$id);
        $stat->execute();
        $result = $stat->fetch();
        $stat->closeCursor();

        return $result;
    }

    public function create (array $data){

        $data = $this->prepareDataCreate($data);
        $query = "INSERT INTO {$this->table} ({$data[0]}) 
            VALUES ({$data[1]})";
        $stat = $this->pdo->prepare($query);

        for($i = 0; $i < count($data[2]); $i++){
            $stat->bindValue("{$data[2][$i]}",$data[3][$i]);
        }

        $result = $stat->execute();
        $stat->closeCursor();
        return $result;
    }

    public  function update(array $data, $id){
        $data = $this->prepareDataUpdate($data);

        $query = "UPDATE {$this->table} SET {$data[0]} WHERE id=:id";
        $stat = $this->pdo->prepare($query);
        $stat->bindValue(":id",$id);
        for ($i =0; $i < count($data[1]);$i ++){
            $stat->bindValue("{$data[1][$i]}",$data[2][$i]);
        }
        $result = $stat->execute();
        $stat->closeCursor();

        return $result;
    }

    public function delete($id){
        $query = "DELETE FROM {$this->table} WHERE id=:id";
        $stat = $this->pdo->prepare($query);
        $stat->bindValue(':id',$id);
        $result = $stat->execute();
        $stat->closeCursor();

        return $result;
    }

    private function prepareDataCreate(array $data){
        $strKeys = "";
        $strBinds = "";
        $binds = [];
        $values = [];

        foreach ($data as $key => $value){
               $strKeys = "{$strKeys},{$key}";
               $strBinds = "{$strBinds},:{$key}";
               $binds[] = ":{$key}";
               $values[] = $value;
        }
        $strKeys = substr($strKeys,1);
        $strBinds = substr($strBinds, 1);

        return [$strKeys,$strBinds,$binds,$values];
    }

    private function  prepareDataUpdate(array  $data){
        $strKeysBinds = "";
        $binds = [];
        $values = [];

        foreach ($data as $key => $value){
            $strKeysBinds = "{$strKeysBinds},{$key}=:{$key}";
            $binds[] = ":{$key}";
            $values[] = $value;
        }
        $strKeysBinds = substr($strKeysBinds,1);

        return [$strKeysBinds,$binds,$values];
    }
}

