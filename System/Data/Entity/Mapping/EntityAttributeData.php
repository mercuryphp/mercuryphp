<?php

namespace System\Data\Entity\Mapping;

class EntityAttributeData {

    protected $tableName;
    protected $key;
    
    public function __construct(array $data){
        
        if(array_key_exists('table', $data)){
            $this->tableName = $data['table'];
        }
        
        if(array_key_exists('fields', $data)){
            $fields = $data['fields'];
            
            if(is_array($fields)){
                foreach($fields as $field){
                    if(array_key_exists('key', $field)){
                        $this->key = $field['name'];
                    }
                }
            }
        }
    }
    
    public function getTableName(){
        return $this->tableName;
    }
    
    public function getKey(){
        return $this->key;
    }
}
