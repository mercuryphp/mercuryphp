<?php

namespace System\Web;

abstract class HttpApplication {
    
    private $rootPath;
    private $routes = null;
    
    public function __construct($rootPath){
        $this->rootPath = $rootPath;
        $this->routes = new \System\Web\Routing\RouteCollection();
    }
    
    protected function getRoutes() : \System\Web\Routing\RouteCollection {
        return $this->routes;
    }

    public function load(){}
}