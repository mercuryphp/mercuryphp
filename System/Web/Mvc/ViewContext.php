<?php

namespace System\Web\Mvc;

class ViewContext {

    protected $httpContext;
    protected $args;
    
    public function __construct(\System\Web\HttpContext $httpContext, $args){
        $this->httpContext = $httpContext;
        $this->args = $args;
    }
    
    public function getHttpContext(){
        return $this->httpContext;
    }
}

