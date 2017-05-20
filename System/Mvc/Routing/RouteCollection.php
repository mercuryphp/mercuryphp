<?php

namespace System\Mvc\Routing;

class RouteCollection implements \IteratorAggregate {
    
    protected $routes = [];
    
    public function add(string $routePattren, array $defaults = []){
        $route = new Route($routePattren, $defaults);
        $this->routes[] = $route;
        return $route;
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->routes);
    }
}

