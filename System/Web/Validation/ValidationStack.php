<?php

namespace System\Web\Validation;

class ValidationStack {
    
    protected $value;
    protected $validators;
    protected $errors = [];
    
    public function __construct($value, array $validators = []){
        $this->value = $value;
        $this->validators = $validators;
    }
    
    public function isValid(){
        foreach($this->validators as $validator){
            $validator->setValue($this->value);
            if(!$validator->isValid()){
                $this->errors[] = $validator->getMessage();
                return false;
            }
        }
        return true;
    }
    
    public function getErrors(){
        return $this->errors;
    }
}
