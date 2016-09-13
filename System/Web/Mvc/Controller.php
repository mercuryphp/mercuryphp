<?php

namespace System\Web\Mvc;

abstract class Controller {
    
    private $rootPath;
    private $httpContext;
    private $viewEngine;


    public function __construct(string $rootPath, \System\Web\Http\HttpContext $httpContext){
        $this->rootPath = $rootPath;
        $this->httpContext = $httpContext;
        $this->viewEngine = new ViewEngine\NativeView();
        $this->viewEngine->setPath($this->rootPath);
    }
    
    public function view(array $data = [], $actionName = null){
        $viewResult = new ViewResult($this->getViewEngine(), new ViewContext($this->httpContext, $data, $actionName));
        return $viewResult;
    }
    
    public function json($data = [], $options = null){
        $jsonResult = new JsonResult($this->httpContext->getResponse(), $data, $options);
        return $jsonResult;
    }
    
    public function getViewEngine(){
        return $this->viewEngine;
    }
    
    public function load(){}
    
    public function render(IActionResult $actionResult){
        $this->httpContext->getResponse()->getOutput()->write($actionResult->execute());
    }
} 
