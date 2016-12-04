<?php

namespace System\Web\Validation;

class ValidationContext {
    
    protected $stackCollection = [];
    protected $errors = [];
    
    public function add(string $value, Validator ...$validators){
        $this->stackCollection[] = new ValidationStack($value, $validators);
    }
    
    public function isValid(){
        foreach($this->stackCollection as $stack){
            if(!$stack->isValid()){
                $this->errors[] = $stack->getErrors();
            }
        }
        return count($this->errors) > 0 ? false : true;
    }
    
    public function getErrors(){
        return $this->errors;
    }
    
}

