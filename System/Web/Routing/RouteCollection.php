<?php

namespace System\Web\Routing;

class RouteCollection implements \IteratorAggregate {
	
	protected $routes = [];
    
	/**
	 * Adds a new Route instance to the route collection. The Route instance
	 * is then returned.
	 *
	 * @param   string $route
	 * @param   array $defaults
	 * @param   array $conditions
	 * @return  System.Web.Routing.Route
	 */
    public function add(string $route, $defaults = [], $conditions = []){
    	$route = new Route($route, $defaults, $conditions);
        $this->routes[] = $route;
        return $this;
    }

    /**
     * Gets a count of all routes in the collection.
     *
     * @return  int
     */
    public function count() : int {
    	return count($this->routes);
    }
    
    public function getIterator(){
    	return new \ArrayIterator($this->routes);
    }
}
