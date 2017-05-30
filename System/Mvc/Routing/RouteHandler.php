<?php

namespace System\Mvc\Routing;

class RouteHandler implements IRouteHandler {
    
    public function execute(\System\Mvc\Http\HttpContext $httpContext, string $routePattern, array $defaults = []){
        
        $request = $httpContext->getRequest();
        $uri = $request->getUrl()->getUri() ? $request->getUrl()->getUri() : 'home/index';

        $routePattern =  str_replace('{', '(?P<', $routePattern);
        $routePattern = '#^' . str_replace('}', '>[a-zA-Z0-9-_.,:;()]+)', $routePattern) . '$#';

        $result = preg_match($routePattern, $uri , $matches);
        
        if($result){
            foreach($matches as $idx=>$match){
                if(is_int($idx)){
                    unset($matches[$idx]);
                }
            }
            return new RouteData(new \System\Core\Arr(array_merge($matches, $defaults)));
        }
        return false;
    }
}

    
