<?php

namespace System\Mvc\Routing;

class RouteCollection implements \IteratorAggregate {
    
    protected $routes = [];
    
    public function add(string $routePattren, array $defaults = []){
        $this->routes[] = new Route($routePattren, $defaults);
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->routes);
    }
}

