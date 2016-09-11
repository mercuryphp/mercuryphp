<?php 

namespace System\Web\Mvc;

class StringResult implements IActionResult {
    
    protected $string = '';
    
    public function __construct($string){
        if(is_scalar($string)){
            $this->string = $string;
        }
    }
    
    public function execute() : string {
        return $this->string;
    }
}