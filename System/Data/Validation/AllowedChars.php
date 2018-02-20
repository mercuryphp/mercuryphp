<?php

namespace System\Data\Validation;

class AllowedChars extends Validator {

    protected $chars;
    
    public function __construct(string $chars, string $errMessage){
        $this->chars = $chars;
        $this->errMessage = $errMessage;
    }
    
    public function isValid() : bool{ 
        $chars = str_split(strtolower($this->value));
        $valueChars = str_split(strtolower($this->chars));
        
        foreach($chars as $char){
            if(!in_array($char, $valueChars)){
                return false;
            }
        }
        return true;
    }
}