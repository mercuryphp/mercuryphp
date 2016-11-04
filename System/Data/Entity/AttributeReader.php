<?php

namespace System\Data\Entity;

use System\Core\Str;
use System\Core\Attribute;

class AttributeReader {
    
    public function read($entityName) : array {
        
        try{
            $class = Attribute::getClassAttributes($entityName); 
        }catch(\ReflectionException $e){
            throw new EntityNotFoundException(sprintf("The entity '%s' does not exist.", $entityName), $e->getCode(), $e, $entityName);
        }
        
        $properties = Attribute::getPropertyAttributes($entityName);

        if(!array_key_exists('System.Data.Entity.TableName', $class)){
            $tableName = new TableName(Str::set($entityName)->split('\\\\')->last()->toLower());
        }else{
            $tableName = $class['System.Data.Entity.TableName'];
        }
        
        if(!array_key_exists('System.Data.Entity.Key', $class)){
            $key = new Key($tableName->getTableName().'_id');
        }else{
            $key = $class['System.Data.Entity.Key'];
        }
        
        return [
            'tableName' => $tableName,
            'key' => $key,
            'properties' => $properties
        ];
    }
}