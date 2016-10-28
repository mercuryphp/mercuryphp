<?php

namespace System\Data;

use \System\Core\Str;
use \System\Core\Obj;
use \System\Data\Database;

class QueryBuilder {
    
    protected $db;
    protected $sql;
    protected $params = [];
    protected $isWhere = false;


    public function __construct(Database $db){
        $this->db = $db;
        $this->sql = new Str();
    }
    
    public function select($tableName, $fields){
        $this->sql = $this->sql->append('SELECT {fields} FROM {table}')->template(['fields' => $fields, 'table' => $tableName]);
        return $this;
    }
    
    public function where(array $params){
        $this->sql = $this->sql->append(' WHERE ');
        foreach($params as $field=>$value){
            $this->sql = $this->sql->append('{field}=:{field}')->template(['field' => $field]);
        }
        $this->params = array_merge($this->params, $params);
        return $this;
    }
    
    public function whereIn($fieldName, array $params){
        $op = "AND";
        if(!$this->isWhere){
            $op = "WHERE";
            $this->isWhere = true;
        }

        $values = join(',', $params);
        
        $this->sql = $this->sql->append(" $op ")->append($fieldName.' IN ('.$values.')')->appendLine();
        return $this;
    }
    
    public function toSingle(array $params = [], string $className = null){
        $data = $this->db->fetch((string)$this->sql, array_merge($this->params, $params), \PDO::FETCH_ASSOC);
        
        if(!$data){
            return false;
        }
        
        if(!$className){
            return $data;
        }
        
        return $this->toEntity($data, $className);
    }
    
    public function toList(array $params = [], string $className = null){
        $data = $this->db->fetchAll((string)$this->sql, array_merge($this->params, $params), \PDO::FETCH_ASSOC);
        $tmp = [];
        
        if(!$className){
            return $data;
        }
        
        if($className){
            foreach ($data as $item) {
                $tmp[] = $this->toEntity($item, $className);
            }
        }
        return $tmp;
    }
    
    protected function toEntity($data, $className){
        $entity = Obj::getInstance($className);
        $entityProperties = Obj::getProperties($entity);

        foreach($entityProperties as $property => $value){
            if(array_key_exists($property, $data)){
                Obj::setPropertyValue($entity, $property, $data[$property]);
            }else{
                throw new EntityException(sprintf("The class property '%s.%s' could not be mapped to the database result.", $className, $property));
            }
        }
        return $entity;
    }
    
    public function __toString(){
        return (string)$this->sql;
    }
}
