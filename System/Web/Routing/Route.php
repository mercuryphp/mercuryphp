<?php

namespace System\Web\Routing;

class Route {
	
    protected $route;
    protected $defaults = [];
    protected $conditions = [];

    public function __construct($route, array $defaults = [], array $conditions = []){
        $this->route = $route;
        $this->defaults = $defaults;
        $this->conditions = $conditions;
        $this->routeHandler = new RouteHandler();
    }

    public function getRoute() : string {
        return $this->route;
    }

    public function getDefaults() : array {
        return $this->defaults;
    }

    public function getConditions() : array {
        return $this->conditions;
    }

    public function setRouteHandler(){
        
    }

    public function getRouteHandler(){
        return $this->routeHandler;
    }
}