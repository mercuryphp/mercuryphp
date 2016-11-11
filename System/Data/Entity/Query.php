<?php

namespace System\Data\Entity;

class Query {
    
    protected $db;
    protected $sql;
    
    public function __construct($db, $entitySchemaCollection, string $sql){
        $this->db = $db;
        $segments = \System\Core\Str::set($sql)->split(' ');
        $this->sql = new \System\Core\Str();
        
        for($idx=0; $idx < count($segments); $idx++){
            $segment = $segments->getString($idx);
            switch($segment->toLower()->trim()){
                case 'from':
                case 'join':
                    $segment = $segment.' '.$entitySchemaCollection->add($segments->get($idx+1))->getTableName();
                    $idx++;
                    break;
            }
            $this->sql = $this->sql->append($segment)->append(' ');
        }
    }
    
    public function toColumn($name, array $params = []){
        $execute = new QueryExecution($this->db, $this->sql);
        return $execute->toColumn($name, $params);
    }

    public function toSingle(string $className, array $params = []){
        $execute = new QueryExecution($this->db, $this->sql);
        return $execute->toSingle($className, $params);
    }

    public function toList(string $className, array $params = []){
        $this->sql = $this->sql->template($params);
        $execute = new QueryExecution($this->db, $this->sql);
        return $execute->toList($className, $params);
    }
    
    public function toArray(array $params = []){
        $this->sql = $this->sql->template($params);
        $execute = new QueryExecution($this->db, $this->sql);
        return $execute->toArray($params);
    }

    public function __toString(){
        return (string)$this->sql;
    }
}

