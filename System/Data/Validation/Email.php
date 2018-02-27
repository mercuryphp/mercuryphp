<?php

namespace System\Data\Validation;

class Email extends Validator {

    public function __construct(string $errMessage){
        $this->errMessage = $errMessage;
    }
    
    public function isValid() : bool{ 
        if(preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $this->value)){
            return true;
        }
        return false;
    }
}