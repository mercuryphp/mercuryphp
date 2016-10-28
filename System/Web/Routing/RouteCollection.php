<?php

namespace System\Web\Routing;

class RouteCollection implements \IteratorAggregate {
	
    protected $routes = [];

    /**
     * Adds a new Route instance to the route collection. The Route instance
     * is then returned.
     */
    public function add(string $route, array $defaults = [], array $conditions = []) : Route {
    	$route = new Route($route, $defaults, $conditions);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Gets a count of all routes in the collection.
     */
    public function count() : int {
    	return count($this->routes);
    }
    
    public function getIterator(){
    	return new \ArrayIterator($this->routes);
    }
}
