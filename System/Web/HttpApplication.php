<?php

namespace System\Web;

use System\Core\Str;
use System\Core\Object;

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
            if($route->getRouteHandler()->execute($route, $this->httpContext)){
                
                $class = Str::set('{namespace}.{module}.Controllers.{controller}Controller')->template(
                    $this->httpContext->getRequest()->getRouteData()->toArray(),
                    ['module' => 'lc.ucf', 'controller' => 'lc.ucf']
                )->trim('.');
                
                try{
                    $controller = Object::getInstance((string)$class);
                }catch(\ReflectionException $e){
                    throw new Mvc\ControllerNotFoundException($this->httpContext, $class);
                }
                
                if(!$controller instanceof Mvc\Controller){
                    throw new Mvc\HttpException(sprintf("The controller '%s' does not inherit from System.Web.Mvc.Controller.", $class));
                }
                break;
            }
    	}
    }
    
    public function error(\Exception $e){}
}