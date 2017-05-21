<?php

namespace System\Data\Entity\Mapping;

use System\Core\Obj;

class EntityAttributeData {

    protected $entityName;
    protected $tableName;
    protected $key;
    protected $fields = [];
    
    public function __construct(string $entityName, array $data){

        $tableNameClass = 'System\Data\Entity\Mapping\Attributes\Table';
        $keyClass = 'System\Data\Entity\Mapping\Attributes\Key';
        
        if(!array_key_exists($tableNameClass, $data)){
            throw new EntityAttributeException(sprintf("Table attribute not found in entity %s.", $data['name']));
        }
        
        if(!$data[$tableNameClass]){
            throw new EntityAttributeException(sprintf("Table name not specified in entity %s.", $data['name']));
        }
        
        if(!array_key_exists($keyClass, $data)){
            throw new EntityAttributeException(sprintf("Key attribute not found in entity %s.", $data['name']));
        }
        
        if(!$data[$keyClass]){
            throw new EntityAttributeException(sprintf("Key name not specified in entity %s.", $data['name']));
        }

        $this->entityName = $entityName;
        $this->tableName = Obj::getInstance($tableNameClass, $data[$tableNameClass])->getName();
        $this->key = Obj::getInstance($keyClass, $data[$keyClass])->getName();

        foreach($data['fields'] as $field => $attributes){
            $this->fields[$field] = $attributes;
        }
    }
    
    public function getEntityName() : string{
        return $this->entityName;
    }
    
    public function getTableName() : string{
        return $this->tableName;
    }
    
    public function getKey() : string{
        return $this->key;
    }
    
    public function getFields() : array{
        return $this->fields;
    }
}
