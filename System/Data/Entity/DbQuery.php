<?php

namespace System\Data\Entity;

use System\Core\Obj;

class DbQuery {
    
    protected $db;
    protected $sql;
    protected $params = [];
    
    public function __construct(\System\Data\Database $db){
        $this->db = $db;
    }
    
    public function setQuery(string $sql, array $params = []){
        $this->sql = $sql;
        $this->params = $params;
        return $this;
    }

    public function toList(string $entityName = ''){

        if(!$entityName){
            $rows = $this->db->fetchAll($this->sql, $this->params);
            return new DbRowCollection($rows);
        }

        $rows = $this->db->fetchAll($this->sql, $this->params, \PDO::FETCH_ASSOC);

        foreach($rows as $idx => $row){
            try {
                $entity = Obj::getInstance($entityName);
                Obj::setProperties($entity, $row);
                $rows[$idx] = $entity;
            } catch (\ReflectionException $re){
                throw new EntityNotFoundException($entityName, $row);
            }
        }
        return new DbRowCollection($rows);
    }
    
    public function single(string $entityName = ''){

        if(!$entityName){
            $rows = $this->db->fetch($this->sql, $this->params);
            return $rows;
        }

        $row = $this->db->fetch($this->sql, $this->params, \PDO::FETCH_ASSOC);

        if($row){
            try {
                $entity = Obj::getInstance($entityName);
                Obj::setProperties($entity, $row);
                return $entity;
            } catch (\ReflectionException $re){
                throw new EntityNotFoundException($entityName, $row);
            }
        }
        return false;
    }
    
    public function column(string $columnName = ''){

        $row = $this->db->fetch($this->sql, $this->params, \PDO::FETCH_ASSOC);
        
        if($columnName){
            if(array_key_exists($columnName, $row)){
                return $row[$columnName];
            }
        }else{
            return array_pop($row);
        }
    }
    
    public function nonQuery(){
        $stm = $this->db->query($this->sql, $this->params);
        return $stm->rowCount();
    }
}
