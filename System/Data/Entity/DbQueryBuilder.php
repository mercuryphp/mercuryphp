<?php

namespace System\Data\Entity;

use System\Core\Str;
use System\Core\StrBuilder;

class DbQueryBuilder {
    
    protected $db;
    protected $sql;
    protected $isWhere = false;
    protected $params = [];
    
    public function __construct(DbContext $db){
        $this->db = $db;
        $this->sql = new StrBuilder();
    }
    
    public function select($fields = '*'){
        $this->sql->append('SELECT ')->append($fields)->appendLine();
        return $this;
    }
    
    public function from(string $entityName){
        $table = $this->getTable($entityName);
        $this->sql->append('FROM ')->append($table->name)
            ->append(" " . $table->alias . " ");

        $this->sql->trim()->appendLine();
        return $this;
    }
    
    public function where(string $field, $value){
        
        if(false == $this->isWhere){
            $this->sql->append("WHERE ");
        }else{
            $this->sql->appendLine()->append("AND ");
        }
        $bindField = str_replace('.', '_', $field);
        $this->sql->append($field)->append('=:')->append($bindField)->appendLine();
        $this->isWhere = true;
        $this->params[$bindField] = $value;
        return $this;
    }
    
    public function orWhere(string $field, $value){
        
        if(false == $this->isWhere){
            $this->sql->append("WHERE ");
        }else{
            $this->sql->appendLine()->append("OR ");
        }
        
        $this->sql->append($field)->append('=:')->append($field)->appendLine();
        $this->isWhere = true;
        $this->params[$field] = $value;
        return $this;
    }
    
    public function whereNot(string $field, $value){
        
        if(false == $this->isWhere){
            $this->sql->append("WHERE ");
        }else{
            $this->sql->appendLine()->append("AND ");
        }
        $bindField = str_replace('.', '_', $field);
        $this->sql->append($field)->append('!=:')->append($bindField)->appendLine();
        $this->isWhere = true;
        $this->params[$bindField] = $value;
        return $this;
    }
    
    public function groupBy($fields){
        $this->sql->append("GROUP BY ")->append($fields)->appendLine();
        return $this;
    }
    
    public function orderBy($fields){
        $this->sql->append("Order BY ")->append($fields)->appendLine();
        return $this;
    }

    public function join(string $entityName, string $condition = ''){
        $this->joinType('JOIN', $entityName, $condition);
        return $this;
    }
    
    public function leftJoin(string $entityName, string $condition = ''){
        $this->joinType('LEFT JOIN', $entityName, $condition);
        return $this;
    }
    
    public function raw(string $sql){
        $this->sql->append($sql)->appendLine();
        return $this;
    }
    
    public function addParam(string $name, $value){
        $this->params[$name] = $value;
        return $this;
    }

    public function setParams(array $params){
        $this->params = array_merge($this->params, $params);
        return $this;
    }
    
    public function getParams() : array{
        return $this->params;
    }

    public function single($entityName = ''){
        return $this->db->query((string)$this->sql, $this->params)->single($entityName);
    }
    
    public function toList($entityName = ''){
        return $this->db->query((string)$this->sql, $this->params)->toList($entityName);
    }
    
    protected function joinType(string $type, string $entityName, string $condition = ''){
        $table = $this->getTable($entityName);
        $this->sql->append($type)->append(" ")
            ->append($table->name)
            ->append(" " . $table->alias . " ");

        if($condition){
            $this->sql->append("ON $condition ")->appendLine();
        }
        return $this;
    }
    
    protected function getTable(string $entityName){
        
        $str = new Str($entityName);
        
        if($str->indexOf(':')){
            $entityName = (string)$str->getIndexOf(':');
            $alias = (string)$str->subString($str->indexOf(':')+1);
        }else{
            $alias = (string)$str->subString($str->lastIndexOf('.'))->getUpperChars()->join()->toLower();
        }

        if($this->db->getEntityAttributeData()->hasKey($entityName)){
            $tableName = $this->db->getEntityAttributeData()->get($entityName)->getTableName();
        }else{
            
            $entityAttributeData = $this->db->getConfiguration()->getEntityAttributeDriver()->read($entityName);
            $this->db->getEntityAttributeData()->add($entityName, $entityAttributeData);
            $tableName = $entityAttributeData->getTableName();
        }
        
        $data = new \stdClass();
        $data->name = $tableName;
        $data->alias = $alias;
        
        return $data;
    }

    public function __toString(){
        return (string)$this->sql;
    }
}
