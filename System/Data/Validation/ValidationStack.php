<?php

namespace System\Data\Validation;

class ValidationStack {
    
    protected $stack;
    protected $value; 
    protected $error;
    
    public function __construct($value){
        $this->stack = new \System\Core\Arr();
        $this->value = $value;
    }
    
    public function validators(array $validators){
        foreach($validators as $validator){
            $this->stack->add($validator);
        }
    }
    
    public function isValid() : bool{
        foreach($this->stack as $validator){
            $validator->setValue($this->value);
            if(!$validator->isValid()){ 
                $this->error = $validator->getMessage();
                return false;
            }
        }
        return true;
    }
    
    public function getError() : string{
        return $this->error;
    }
}