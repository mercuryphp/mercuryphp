<?php

namespace System\Data;

class DbFunction {
    
    protected $functionName;
    protected $args = array();
    
    public function __construct($functionName, array $args = array()){
        $this->functionName = strtoupper($functionName);
        $this->args = $args;
    }
    
    public function getName() : string {
        return $this->functionName;
    }
    
    public function getArgs() : array {
        return $this->args;
    }
    
    public function toString() : string {
        return \System\Core\Str::set('{func}({args})')->tokens([
            'func' => $this->functionName, 
            'args' => join(',', $this->args)
        ]);
    }
}