<?php

namespace System\Web\Validation;

abstract class Validator {
    
    protected $value;
    protected $message;
    
    public function setValue(string $value){
        $this->value = $value;
    }
    
    public function getMessage() : string{
        return $this->message;
    }
    
    public abstract function isValid() : bool;
}