<?php

namespace System\Web\Routing;

use System\Core\Str;

class RouteHandler {
    
    public function execute(Route $route, \System\Web\HttpContext $httpContext){
        
        $uri = $httpContext->getRequest()->getUri();
        
        $tokens = Str::set($route->getRoute())->tokenize('{', '}');

        $routePattern = '';
        foreach($tokens as $token){
            if(substr($token, 0,1) == '{'){
                $routePattern .= '(?<'. trim($token, '{}').'>.+)';
            }else{
                $routePattern .= $token;
            }
        }

        preg_match('#^'.$routePattern.'/$#', $uri, $matches);

        print_r($matches);

    }
}