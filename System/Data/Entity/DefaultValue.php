<?php

namespace System\Data\Entity;

class DefaultValue {
    
    protected $value; 
    
    public function __construct($value){
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}