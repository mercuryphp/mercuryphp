<?php

namespace System\Data\Validation;

use System\Core\Arr;
use System\Core\Obj;

class ValidationContext {
    
    protected $context;
    protected $errors;
    
    public function __construct(){
        $this->context = new Arr();
        $this->errors = new Arr();
    }
    
    public function add(string $name, $value){
        if(is_object($value)){
            $value = Obj::getProperty($value, $name);
        }
        $stack = new ValidationStack($value);
        $this->context->set($name, $stack);
        return $stack;
    }
    
    public function addError(string $name, string $errMessage){
        $this->errors->set($name, $errMessage);
        return $this;
    }
    
    public function isValid() : bool{
        foreach($this->context as $name => $stack){
            if(!$stack->isValid()){
                $this->errors->set($name, $stack->getError());
            }
        }
        return $this->errors->count() > 0 ? false : true;
    }

    public function getErrors() : Arr{
        return $this->errors;
    }
}