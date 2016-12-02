<?php

namespace System\Data\Entity;

use System\Core\Obj;

class QueryExecution {
    
    protected $db;
    protected $sql;
    
    public function __construct($db, $sql){
        $this->db = $db;
        $this->sql = $sql;
    }
    
    public function toColumn(string $name, array $params = []){
        $data = $this->db->fetch((string)$this->sql, $params, \PDO::FETCH_ASSOC);
        
        if(array_key_exists($name, $data)){
            return $data[$name];
        }
        return false;
    }

    public function toSingle(string $className, array $params = []){
        $data = $this->db->fetch((string)$this->sql, $params, \PDO::FETCH_ASSOC);
        
        if(!$data){
            return false;
        }
        
        return $this->toEntity($data, $className);
    }
    
    public function toList(string $className, array $params = []){
        $data = $this->db->fetchAll((string)$this->sql, $params, \PDO::FETCH_ASSOC);

        if($className){
            foreach ($data as $idx=>$item) {
                $data[$idx] = $this->toEntity($item, $className);
            }
        }
        return new DbResultList($data);
    }
    
    public function toArray(array $params = []){
        return new DbResultList($this->db->fetchAll((string)$this->sql, $params, \PDO::FETCH_ASSOC));
    }
    
    public function nonQuery(array $params = []){
        $stm = $this->db->query((string)$this->sql, $params);
        return $stm->rowCount();
    }


    protected function toEntity($data, $className){
        try{
            $entity = Obj::getInstance($className);
        }catch(\ReflectionException $re){
            throw new EntityNotFoundException(sprintf("The entity '%s' does not exist.", $className), $re->getCode(), $re, $className, $data);
        }
        $entityProperties = Obj::getProperties($entity);

        foreach($entityProperties as $property => $value){
            if(array_key_exists($property, $data)){
                Obj::setPropertyValue($entity, $property, $data[$property]);
            }else{
                if(substr($property, 0,1) !='_'){
                    throw new EntityException(sprintf("The class property '%s.%s' could not be mapped to the database result.", $className, $property));
                }
            }
        }
        return $entity;
    }
}
