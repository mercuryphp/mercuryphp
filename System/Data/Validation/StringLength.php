<?php

namespace System\Data\Validation;

class StringLength extends Validator {

    protected $length;
    
    public function __construct(int $length, string $errMessage){
        $this->length = $length;
        $this->errMessage = $errMessage;
    }
    
    public function isValid() : bool{
        if(is_string($this->value) && (strlen($this->value) <= $this->length)){
            return true;
        }
        return false;
    }
}