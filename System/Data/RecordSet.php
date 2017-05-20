<?php

namespace System\Data;

class RecordSet{
    
    protected $row = [];
    
    public function __construct(array $row){
        $this->row = $row;
    }
    
    public function getString(string $field, $default = null){
        return new \System\Core\Str($this->get($field, $default));
    }
    
    public function get(string $field, $default = null){
        if($this->hasField($field)){
            return $this->row[$field];
        }
        if(null != $default){
            return $default;
        }
    }

    public function hasField($field){
        if(array_key_exists($field, $this->row)){
            return true;
        }
        return false;
    }
}

