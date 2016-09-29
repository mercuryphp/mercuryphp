<?php

namespace System\Web\Routing;

use System\Core\Str;

class RouteHandler implements IRouteHandler {
    
    public function execute(Route $route, \System\Web\Http\HttpContext $httpContext) : bool {
        
        $httpContext->getRequest()->getRouteData()->merge($route->getDefaults());
        
        $uriPath = $httpContext->getRequest()->getUrl()->getPath();

        $tokens = Str::set($route->getRoute())->tokenize('{', '}')[1];

        $pathSegments = Str::set($uriPath)->replace($tokens, '#', 1)->split('#');

        $counter=0;
        foreach($tokens as $idx=>$token){ 
            if(substr($token, 0,1) == '{'){
                $tokenName = Str::set($token)->get('{', '}');
                $tokens[$idx] = $pathSegments->get($counter);
                
                if($tokens[$idx]){
                    $httpContext->getRequest()->getRouteData()->set((string)$tokenName, $tokens[$idx]);
                    $httpContext->getRequest()->getParams()->set((string)$tokenName, $tokens[$idx]);
                }
                ++$counter;
            }
        }

        if(Str::join('',$tokens)->trim('/')->equals($uriPath)){
            return true;
        }
        return false;
    }
}