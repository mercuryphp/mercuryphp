<?php

namespace System\Data\Entity;

class EntitySchema {
    
    protected $tableName;
    protected $key;
    protected $properties;


    public function __construct(array $schema){
        $this->tableName = $schema['tableName']->getTableName();
        $this->key = $schema['key']->getKey();
        $this->properties = $schema['properties']; 
    }
    
    public function getTableName() : string {
        return $this->tableName;
    }

    public function getKey() : string {
        return $this->key;
    }
    
    public function getProperties() : array {
        return $this->properties;
    }
    
    public function getProperty(string $name){
        return array_key_exists($name, $this->properties) ? $this->properties[$name] : false;
    }
}