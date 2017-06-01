<?php

namespace System\Data\Validation;

abstract class Validator {
    
    protected $value;
    protected $errMessage;
    
    public function setValue($value){
        $this->value = $value;
    }
    
    public function getMessage(){
        return $this->errMessage;
    }
    
    public abstract function isValid() : bool;
}