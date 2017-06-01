<?php

namespace System\Data\Validation;

use System\Core\Arr;

class ValidationContext {
    
    protected $context;
    protected $errors;
    
    public function __construct(){
        $this->context = new Arr();
        $this->errors = new Arr();
    }
    
    public function add(string $name, $value){
        $stack = new ValidationStack($value);
        $this->context->add($stack, $name);
        return $stack;
    }
    
    public function isValid() : bool{
        foreach($this->context as $name => $stack){
            if(!$stack->isValid()){
                $this->errors->add($stack->getError(), $name);
            }
        }
        return $this->errors->count() > 0 ? false : true;
    }

    public function getErrors() : Arr{
        return $this->errors;
    }
}