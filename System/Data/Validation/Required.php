<?php

namespace System\Data\Validation;

class Required extends Validator {

    public function __construct(string $errMessage = ''){
        $this->errMessage = $errMessage ? $errMessage : 'Is required';
    }
    
    public function isValid() : bool{
        if($this->value){
            return true;
        }
        return false;
    }
}