<?php

namespace System\Web\Http;

class HttpMethodAttribute extends \System\Web\Mvc\FilterAttribute {
    
    public function __construct(){
        $this->methods = array_map('strtoupper', func_get_args());
    }
    
    public function isValid(\System\Web\Mvc\Controller $controller){
        if(!in_array($controller->getHttpContext()->getRequest()->getHttpMethod(), $this->methods)){
            throw new \System\Web\Mvc\ActionNotFoundException($controller->getHttpContext(), get_class($controller));
        }
        return true;
    }
}