<?php

namespace System\Mvc;

class StringResult implements IActionResult {
    
    protected $string;
    
    public function __construct($string){
        $this->string = $string;
    }
    
    public function execute() : string{
        return (string)$this->string;
    }
}