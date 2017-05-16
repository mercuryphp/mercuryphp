<?php

namespace System\Data\Entity;

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
    
    public function select($fields){
        $this->sql->append('SELECT ')->append($fields)->appendLine();
        return $this;
    }
    
    public function from($table, $alias = ''){
        $this->sql->append('FROM ')->append($table);
        
        if($alias){
            $this->sql->append(" $alias ");
        }
        $this->sql->trim()->appendLine();
        return $this;
    }
    
    public function where($expr, array $params = []){
        
        if(false == $this->isWhere){
            $this->sql->append("WHERE ");
        }else{
            $this->sql->appendLine()->append("AND ");
        }
        
        $this->sql->append($expr)->appendLine();
        $this->isWhere = true;
        $this->params = array_merge($this->params, $params);
        return $this;
    }
    
    public function orWhere($expr, array $params = []){
        
        if(false == $this->isWhere){
            $this->sql->append("WHERE ");
        }else{
            $this->sql->appendLine()->append("OR ");
        }
        
        $this->sql->append($expr)->appendLine();
        $this->isWhere = true;
        $this->params = array_merge($this->params, $params);
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

    public function join($table, $alias = '', $condition = ''){
        $this->sql->append("JOIN ")->append($table);
        
        if($alias){
            $this->sql->append(" $alias ");
        }
        
        if($condition){
            $this->sql->append("ON $condition ")->appendLine();
        }
        return $this;
    }
    
    public function toList($entityName = '', array $params = []){
        $this->params = array_merge($this->params, $params);
        return $this->db->query((string)$this->sql, $this->params)->toList($entityName);
    }

    public function __toString(){
        return (string)$this->sql;
    }
}
