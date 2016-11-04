<?php

namespace System\Web\Mvc;

class RedirectResult implements IActionResult {

    protected $httpContext;
    protected $location;
    protected $flashSessionValue;
    
    public function __construct($httpContext, $location){
        $this->httpContext = $httpContext;
        $this->location = $location;
    }
    
    public function with($value){
        $this->flashSessionValue = $value;
        return $this;
    }
    
    public function execute() : string {
        $this->httpContext->getSession()->set('__FLASH_SESSION__', $this->flashSessionValue);
        $this->httpContext->getResponse()->redirect($this->location); 
        return '';
    }
}