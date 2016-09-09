<?php

namespace System\Web\Mvc;

abstract class Controller {
    
    private $rootPath;
    private $httpContext;
    private $viewEngine;


    public function __construct(string $rootPath, \System\Web\HttpContext $httpContext){
        $this->rootPath = $rootPath;
        $this->httpContext = $httpContext;
        $this->viewEngine = new ViewEngine\NativeView();
    }
    
    public function view($params = [], $actionName = null){
        $viewResult = new ViewResult($this->getViewEngine(), new ViewContext($this->rootPath, $this->httpContext, $params, $actionName));
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
