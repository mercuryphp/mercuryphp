<?php

namespace System\Web\Mvc\ViewEngine;

class TemplateToken {
    
    protected $type;
    protected $value;
    protected $lineNumber;
    
    const T_STRING = 1;
    const T_CODE = 2;
    const T_SPACE = 3;
    const T_OPERATOR = 4;
    const T_TEN = 5;
    const T_NAK = 6;
    const T_DOUBLE_QUOTE = 7;
    
    public function __construct($type, $value, $lineNumber = 0){
        $this->type = $type;
        $this->value = \System\Core\Str::set($value);
        $this->lineNumber = $lineNumber;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function getValue(){
        return $this->value;
    }
    
    public function getLineNumber(){
        return $this->lineNumber;
    }
}

