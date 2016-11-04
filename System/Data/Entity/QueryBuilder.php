<?php

namespace System\Data\Entity;

use \System\Core\Str;
use \System\Core\Obj;
use \System\Data\Database;

class QueryBuilder {
    
    protected $db;
    protected $sql;
    protected $params = [];
    protected $isWhere = false;

    public function __construct(Database $db, $tableName, $fields){
        $this->db = $db;
        $this->sql = (new Str('SELECT {fields} FROM {table}'))->template(['fields' => $fields, 'table' => $tableName]);
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
    
    public function toSingle(string $className, array $params = []){
        $execute = new QueryExecution($this->db, $this->sql);
        return $execute->toSingle($className, array_merge($this->params, $params));
    }

    public function toList(string $className, array $params = []){
        $execute = new QueryExecution($this->db, $this->sql);
        return $execute->toList($className, array_merge($this->params, $params));
    }

    public function __toString(){
        return (string)$this->sql;
    }
}
