<?php

namespace System\Web\Mvc;

class ViewContext {

    protected $httpContext;
    protected $params;
    protected $actionName;
    
    public function __construct(\System\Web\Http\HttpContext $httpContext, array $params, string $actionName = null){
        $this->httpContext = $httpContext;
        $this->params = $params;
        $this->actionName = $actionName;
    }

    public function getHttpContext() : \System\Web\Http\HttpContext {
        return $this->httpContext;
    }
    
    public function getParams() : array {
        return $this->params;
    }
    
    public function getActionName() : string {
        return $this->actionName;
    }
}

