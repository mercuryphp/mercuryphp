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
    
    public function getRequest(){
        return $this->httpContext->getRequest();
    }
    
    public function view(array $data = [], $actionName = null){
        $viewResult = new ViewResult($this->getViewEngine(), new ViewContext($this->httpContext, $data, $actionName));
        return $viewResult;
    }
    
    public function json($data = [], $options = null){
        $jsonResult = new JsonResult($this->httpContext->getResponse(), $data, $options);
        return $jsonResult;
    }
    
    public function setViewEngine(ViewEngine\View $viewEngine) : Controller {
        $this->viewEngine = $viewEngine;
        $this->viewEngine->setPath($this->rootPath);
        return $this;
    }
    
    public function getViewEngine() : ViewEngine\View {
        return $this->viewEngine;
    }
    
    public function load(){}
    
    public function render(IActionResult $actionResult){
        $this->httpContext->getResponse()->getOutput()->write($actionResult->execute());
    }
} 
