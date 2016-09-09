<?php

namespace System\Web\Mvc;

abstract class Controller {
    
    private $httpContext;
    private $viewEngine;


    public function __construct(\System\Web\HttpContext $httpContext){
        $this->httpContext = $httpContext;
        $this->viewEngine = new ViewEngine\NativeView();
    }
    
    public function view($args = null){
        $viewResult = new ViewResult($this->getViewEngine(), new ViewContext($this->httpContext, $args));
        return $viewResult;
    }
    
    public function getViewEngine(){
        return $this->viewEngine;
    }
    
    public function load(){}
    
    public function render(IActionResult $actionResult){
        $this->httpContext->getResponse()->getOutput()->write($actionResult->execute());
    }
} 
