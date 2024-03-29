<?php

namespace System\Data\Validation;

class Required extends Validator {

    public function __construct(string $errMessage){
        $this->errMessage = $errMessage;
    }
    
    public function isValid() : bool{ 
        if(strlen($this->value) > 0){
            return true;
        }
        return false;
    }
}