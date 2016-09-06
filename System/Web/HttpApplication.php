<?php

namespace System\Web;

abstract class HttpApplication {
    
    private $rootPath;
    private $routes = null;
    
    public function __construct($rootPath){
        $this->rootPath = $rootPath;
        $this->routes = new \System\Web\Routing\RouteCollection();
        $this->httpContext = new HttpContext(new HttpRequest());
    }
    
    protected function getRoutes(){
        return $this->routes;
    }

    public function load(){}
    
    public final function run(){
    	
    	if(!$this->routes->count()){
            throw new \RuntimeException('One or more routes must be registered.');
    	}
    	
    	foreach($this->routes as $route){
            $route->getRouteHandler()->execute($this->httpContext);
    	}
    }
    
    public function error(\Exception $e){}
}