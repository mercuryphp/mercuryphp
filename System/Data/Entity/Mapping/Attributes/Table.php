<?php

namespace System\Data\Entity\Mapping\Attributes;

class Table {
    
    protected $tableName;
    
    public function __construct(string $tableName){
        $this->tableName = $tableName;
    }
    
    public function getName(){
        return $this->tableName;
    }
}