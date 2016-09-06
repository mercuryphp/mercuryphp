<?php

namespace System\Web;

abstract class HttpApplication {
    
    private $rootPath;
    private $routes = null;
    
    public function __construct($rootPath){
        $this->rootPath = $rootPath;
        $this->routes = new \System\Web\Routing\RouteCollection();
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
    		$route->execute();
    	}
    }
    
    public function error(\Exception $e){}
}