<?php

namespace System\Data\Entity;

class EntitySchemaCollection {
    
    protected $collection = [];
    protected $schemaReader;
    
    public function __construct($schemaReader){
        $this->schemaReader = $schemaReader;
    }
    
    public function add($entityName) : EntitySchema {
        $entityName = str_replace('.', '\\', $entityName);
        if($this->hasSchema($entityName)){
            return $this->collection[$entityName];
        }
        $this->collection[$entityName] = new EntitySchema($this->schemaReader->read($entityName));
        return $this->collection[$entityName];
    }
    
    public function hasSchema($entityName){
        $entityName = str_replace('.', '\\', $entityName);
        if(array_key_exists($entityName, $this->collection)){
            return true;
        }
        return false;
    }
    
    public function getSchema($entityName){
        if(is_object($entityName)){
            $entityName = get_class($entityName);
        }
        $entityName = str_replace('.', '\\', $entityName);
        return array_key_exists($entityName, $this->collection) ? $this->collection[$entityName] : false;
    }
}