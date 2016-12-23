<?php

namespace System\Web\Validation;

class Contains extends Validator {
    
    protected $collection;
    
    public function __construct(array $collection, string $message){
        $this->collection = $collection;
        $this->message = $message;
    }
    
    public function isValid() : bool {
        if (in_array($this->value, $collection)){
            return true;
        }
        return false;
    }
}