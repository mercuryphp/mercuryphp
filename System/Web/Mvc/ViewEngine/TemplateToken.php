<?php

namespace System\Web\Mvc\ViewEngine;

class TemplateToken {
    
    protected $type;
    protected $value;
    protected $line;
    
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
    
    public function getLine(){
        return $this->value;
    }
}

