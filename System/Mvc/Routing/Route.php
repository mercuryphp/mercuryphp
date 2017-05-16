<?php

namespace System\Mvc\Routing;

class Route {
    
    protected $routePattern;
    protected $defaults;
    protected $routeHandler;
    
    public function __construct(string $routePattern, array $defaults = []){
        $this->routePattern = $routePattern;
        $this->defaults = $defaults;
        $this->routeHandler = new RouteHandler();
    }
    
    public function execute(\System\Mvc\Http\Request $request){
        return $this->routeHandler->execute($request, $this->routePattern, $this->defaults);
    }
}