<?php

namespace System\Web\Routing;

use System\Core\Str;

class RouteHandler {
    
    public function execute(Route $route, \System\Web\HttpContext $httpContext){
        
        $uri = $httpContext->getRequest()->getUri();
        
        $tokens = Str::set($route->getRoute())->tokenize('{', '}');

        $uriSegments = Str::set($uri)->replace($tokens, '#', 1)->split('#');

        $counter=0;
        foreach($tokens as $idx=>$token){ 
            if(substr($token, 0,1) == '{'){
                $tokenName = Str::set($token)->get('{', '}');
                $tokens[$idx] = $uriSegments->get($counter);
                ++$counter;
            }
            
        }
        
        print_R($tokens); exit;
    }
}