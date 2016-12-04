<?php

namespace System\Web\Validation;

class ValidationContext {
    
    protected $stackCollection = [];
    protected $errors = [];
    
    public function add(string $value, Validator ...$validators) : ValidationStack{
        $stack = new ValidationStack($value, $validators);
        $this->stackCollection[] = $stack;
        return $stack;
    }
    
    public function isValid(){
        foreach($this->stackCollection as $stack){
            if(!$stack->isValid()){
                if($stack->getName()){
                    $this->errors[$stack->getName()] = $stack->getErrors();
                }else{
                    $this->errors[] = $stack->getErrors();
                }
            }
        }
        return count($this->errors) > 0 ? false : true;
    }
    
    public function getErrors(){
        return $this->errors;
    }
    
}

