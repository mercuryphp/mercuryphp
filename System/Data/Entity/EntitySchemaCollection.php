<?php

namespace System\Data\Entity;

class EntitySchemaCollection {
    
    protected $collection = [];
    protected $schemaReader;
    
    public function __construct($schemaReader){
        $this->schemaReader = $schemaReader;
    }
    
    public function add($entityName) : EntitySchema {
        $this->collection[$entityName] = new EntitySchema($this->schemaReader->read($entityName));
        return $this->collection[$entityName];
    }
    
    public function getSchema($entityName){
        if(is_object($entityName)){
            $entityName = get_class($entityName);
        }
        return array_key_exists($entityName, $this->collection) ? $this->collection[$entityName] : false;
    }
}