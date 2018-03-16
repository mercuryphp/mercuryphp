<?php

namespace System\Data\Validation;

class Compare extends Validator {

    protected $compareString;
    
    public function __construct(string $compareString, string $errMessage){
        $this->compareString = $compareString;
        $this->errMessage = $errMessage;
    }
    
    public function isValid() : bool{ 
        if($this->value == $this->compareString){
            return true;
        }
        return false;
    }
}