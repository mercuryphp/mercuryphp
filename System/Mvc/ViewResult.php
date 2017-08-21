<?php

namespace System\Mvc;

class ViewResult implements IActionResult {
    
    protected $view;
    protected $httpContext;
    protected $params = [];
    protected $viewName;

    public function __construct(View\View $view, Http\HttpContext $httpContext, array $params = []){
        $this->view = $view;
        $this->httpContext = $httpContext;
        $this->params = $params;
    }
    
    public function setName(string $viewName){
        $this->viewName = $viewName;
        return $this;
    }
    
    public function setLayout(string $layout){
        $this->view->setLayout($layout);
        return $this;
    }
    
    public function setContentType(string $contentType){
        $this->httpContext->getResponse()->setContentType($contentType);
        return $this;
    }

    public function execute() : string{
        $viewName = $this->viewName ? $this->viewName : $this->httpContext->getRequest()->getRouteData()->getAction();
        return $this->view->render($this->httpContext, $this->params, $viewName);
    }
}