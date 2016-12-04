<?php

namespace System\Web\Validation;

class Required extends Validator {
    
    public function __construct($message){
        $this->message = $message;
    }
    
    public function isValid() : bool {
        if (strlen($this->value) > 0){
            return true;
        }
        return false;
    }
}