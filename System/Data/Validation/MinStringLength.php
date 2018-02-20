<?php

namespace System\Data\Validation;

class MinStringLength extends Validator {

    protected $length;
    
    public function __construct(int $length, string $errMessage){
        $this->length = $length;
        $this->errMessage = $errMessage;
    }
    
    public function isValid() : bool{
        if(strlen($this->value) < $this->length){
            return false;
        }
        return true;
    }
}