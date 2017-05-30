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
    
    public function setRouteHandler(IRouteHandler $routeHandler){
        $this->routeHandler = $routeHandler;
    }

    public function execute(\System\Mvc\Http\HttpContext $httpContext){
        return $this->routeHandler->execute($httpContext, $this->routePattern, $this->defaults);
    }
}