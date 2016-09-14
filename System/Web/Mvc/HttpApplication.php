<?php

namespace System\Web\Mvc;

use System\Core\Str;
use System\Core\Object;
use System\Web\Routing\RouteCollection;
use System\Web\Http\HttpContext;
use System\Web\Http\HttpRequest;
use System\Web\Http\HttpResponse;

abstract class HttpApplication {
    
    private $rootPath;
    private $routes;
    
    public function __construct(string $rootPath){
        $this->rootPath = $rootPath;
        $this->routes = new RouteCollection();
        $this->httpContext = new HttpContext(new HttpRequest(), new HttpResponse());
        $this->config = new \System\Configuration\Configuration('config.php');
    }
    
    protected function getRoutes() : \System\Web\Routing\RouteCollection {
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
                    $controller = Object::getInstance((string)$class, [
                        $this->rootPath,
                        $this->httpContext
                    ]);
                }catch(\ReflectionException $e){
                    throw new ControllerNotFoundException($this->httpContext, $class);
                }
                
                if(!$controller instanceof Controller){
                    throw new HttpException(sprintf("The controller '%s' does not inherit from System.Web.Mvc.Controller.", $class));
                }
                
                $refClass = new \ReflectionClass($controller);
                $actionName = $this->httpContext->getRequest()->getRouteData()->get('action');
                
                if(!$refClass->hasMethod($actionName)){
                    throw new ActionNotFoundException($this->httpContext, get_class($controller));
                }
                
                $this->beforeAction($controller);
                
                $controller->load();
                
                $actionMethod = $refClass->getMethod($actionName);
                
                $actionResult = $actionMethod->invokeArgs($controller, []);
                
                if(!$actionResult instanceof \System\Web\Mvc\IActionResult){
                    if(is_array($actionResult)){
                        $actionResult = new \System\Web\Mvc\JsonResult($this->httpContext->getResponse(), $actionResult, null);
                    }else{
                        $actionResult = new \System\Web\Mvc\StringResult($actionResult);
                    }
                }
                
                $controller->render($actionResult);
                
                break;
            }
    	}
    }
    
    public function beforeAction(Controller $controller){ 
    }
    
    public function end(){
        $this->httpContext->getResponse()->flush();
    }

    public function error(\Exception $e){}
}