<?php

namespace System\Web\Routing;

class Route {
	
    protected $route;
    protected $defaults = [];
    protected $conditions = [];
    protected $routeHandler;

    public function __construct($route, array $defaults = [], array $conditions = []){
        $this->route = $route;
        $this->defaults = new \System\Collections\Dictionary($defaults);
        $this->conditions = new \System\Collections\Dictionary($conditions);
        $this->routeHandler = new RouteHandler();
    }

    public function getRoute() : string {
        return $this->route;
    }

    public function getDefaults() : \System\Collections\Dictionary {
        return $this->defaults;
    }

    public function getConditions() : \System\Collections\Dictionary {
        return $this->conditions;
    }

    public function setRouteHandler(IRouteHandler $routeHandler){
        $this->routeHandler = $routeHandler;
    }

    public function getRouteHandler() : IRouteHandler {
        return $this->routeHandler;
    }
}