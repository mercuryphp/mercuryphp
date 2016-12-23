<?php

namespace System\Web\Validation;

class Alphabetic extends Validator {

    public function __construct(string $message){
        $this->message = $message;
    }
    
    public function isValid() : bool {
        if(preg_match('/[a-zA-Z ]*$/', $this->value)){
            return true;
        }
        return false;
    }
}