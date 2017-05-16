<?php

namespace System\Mvc\Http;

class HttpMethod {
    
    public function execute(array $methods, $json = false){
        
        $httpMethod = strtolower($this->getRequest()->getHttpMethod());
        
        if(in_array($httpMethod, array_map("strtolower", $methods))){
            return true;
        }
        
        if($json){
            $this->getResponse()->toJson(['status' => 405, 'message' => 'Method Not Allowed']);
            return false;
        }
        
        $routeData = $this->getRequest()->getRouteData();
        throw new \System\Mvc\Http\HttpMethodNotAllowedException(sprintf('The Action %s:%s() does not allow the "%s" method.', get_class($this), $routeData->getAction(), strtoupper($httpMethod)));
    }
}