<?php

namespace System\Web\Mvc\ViewEngine;

class TemplateToken {
    
    protected $type;
    protected $value;
    protected $line;
    
    const T_STRING = 1;
    const T_CODE = 2;
    
    public function __construct($type, $value, $line){
        $this->type = $type;
        $this->value = $value;
        $this->line = $line;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function getValue(){
        return $this->value;
    }
    
    public function getLineNumber(){
        return $this->line;
    }
}

