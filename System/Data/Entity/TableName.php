<?php

namespace System\Data\Entity;

class TableName {
    
    private $tableName;
    
    public function __construct($tableName){
        $this->tableName = $tableName;
    }
    
    public function getTableName(){
        return $this->tableName;
    }
}
