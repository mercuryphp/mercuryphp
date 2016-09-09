<?php

namespace System\Web\Mvc;

class ViewContext {

    protected $rootPath;
    protected $httpContext;
    protected $params;
    protected $actionName;
    
    public function __construct(string $rootPath, \System\Web\HttpContext $httpContext, array $params, string $actionName = null){
        $this->rootPath = $rootPath;
        $this->httpContext = $httpContext;
        $this->params = $params;
        $this->actionName = $actionName;
    }
    
    public function getRootPath() : string {
        return $this->rootPath;
    }
    
    public function getHttpContext() : \System\Web\HttpContext {
        return $this->httpContext;
    }
    
    public function getParams() : array {
        return $this->params;
    }
    
    public function getActionName() : string {
        return $this->actionName;
    }
}

