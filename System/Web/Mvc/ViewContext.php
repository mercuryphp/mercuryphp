<?php

namespace System\Web\Mvc;

class ViewContext {

    protected $httpContext;
    protected $params;
    protected $viewName;
    
    public function __construct(\System\Web\Http\HttpContext $httpContext, array $params, string $viewName = ''){
        $this->httpContext = $httpContext;
        $this->params = $params;
        $this->viewName = $viewName;
    }

    public function getHttpContext() : \System\Web\Http\HttpContext {
        return $this->httpContext;
    }
    
    public function getParams() : array {
        return $this->params;
    }
    
    public function getViewName() : string {
        return $this->viewName;
    }
}

