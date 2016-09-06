<?php

namespace System\Web\Routing;

use System\Core\Str;

class RouteHandler {
    
    public function execute(Route $route, \System\Web\HttpContext $httpContext){
        
        $uri = $httpContext->getRequest()->getUri();
        
        $tokens = Str::set($route->getRoute())->tokenize('{', '}');

    }
}