<?php

namespace System\Web\Routing;

use System\Core\Str;

class RouteHandler implements IRouteHandler {
    
    public function execute(Route $route, \System\Web\Http\HttpContext $httpContext) : bool {
        
        $httpContext->getRequest()->getRouteData()->merge($route->getDefaults());
        
        $uri = $httpContext->getRequest()->getUri();
        
        $tokens = Str::set($route->getRoute())->tokenize('{', '}')[1];

        $uriSegments = Str::set($uri)->replace($tokens, '#', 1)->split('#');

        $counter=0;
        foreach($tokens as $idx=>$token){ 
            if(substr($token, 0,1) == '{'){
                $tokenName = Str::set($token)->get('{', '}');
                $tokens[$idx] = $uriSegments->get($counter);
                
                if($tokens[$idx]){
                    $httpContext->getRequest()->getRouteData()->set((string)$tokenName, $tokens[$idx]);
                }
                ++$counter;
            }
        }

        if(Str::join('',$tokens)->trim('/')->equals($uri)){
            return true;
        }
        return false;
    }
}