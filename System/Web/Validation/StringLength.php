<?php

namespace System\Web\Validation;

class StringLength extends Validator {
    
    protected $length;
    
    public function __construct(int $length, string $message){
        $this->length = $length;
        $this->message = $message;
    }
    
    public function isValid() : bool {
        if ((strlen($this->value) > 0) && (strlen($this->value) <= $this->length)){
            return true;
        }
        return false;
    }
}