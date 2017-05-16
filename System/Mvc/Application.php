<?php

namespace System\Mvc;

use System\Core\Environment;
use System\Core\Obj;
use System\Core\Str;
use System\Core\Configuration;
use System\Core\ExceptionHandler;
use System\Mvc\Routing\RouteCollection;
use System\Mvc\Http\HttpContext;
use System\Mvc\Http\Request;
use System\Mvc\Http\Response;
use System\Mvc\Http\Session\FileSession;

abstract class Application {
    
    private $environment;
    private $httpContext;
    private $exceptionHandler;
    private $routes;
    private $config = null;
    private $services;
    private $viewEngine;
    
    public function start(Environment $environment){
        $this->environment = $environment;
        $this->httpContext = new HttpContext($this->environment, new Request(), new Response(), new FileSession());
        $this->exceptionHandler = new ExceptionHandler($this->httpContext->getResponse());
        $this->routes = new RouteCollection();
        $this->viewEngine = new \System\Mvc\View\NativeView($this->environment->getRootDirectory());
        
        $configFile = $environment->getRootDirectory().'/config.php';
        
        if(is_file($configFile)){
            $this->config = new Configuration($configFile);
            $viewMethods = $this->config->get('system.view.methods');

            if(is_array($viewMethods)){
                foreach($viewMethods as $name => $classMethod){
                    $this->viewEngine->addMethod($name, $classMethod);
                }
            }
        }

        $session = $this->httpContext->getSession();
        $request = $this->httpContext->getRequest();
        
        if($request->getCookie()->hasCookie($session->getName())){
            $session->setSessionId($request->getCookie($session->getName())->getValue());
        } 
    }
    
    protected function getRoutes() : RouteCollection{
        return $this->routes;
    }
    
    protected function getEnvironment() : Environment{
        return $this->environment;
    }

    public function load(){}
    
    public function run(){
        foreach($this->routes as $route){
            $routeData = $route->execute($this->httpContext->getRequest());
            
            if($routeData){
                $controllerName = (string)Str::set('App\{module}\Controllers\{controller}Controller')->tokens([
                    'module' => Str::set($routeData->getModule())->toLower()->toUpperFirst(),
                    'controller' => Str::set($routeData->getController())->toLower()->toUpperFirst()
                ])->replace('\\\\', '\\');

                $this->httpContext->getRequest()->setRouteData($routeData);
                
                try{
                    $controller = Obj::getInstance($controllerName);
                    
                    if(!$controller instanceof \System\Mvc\Controller){
                        throw new HttpException('Controller ' . $controllerName . ' must be an instance of System\Mvc\Controller.');
                    }
                    
                    $controller->getRegistry()->merge(Obj::getProperties($this));
                }catch(\Exception $e){
                    throw new HttpException($e->getMessage().'.');
                }
                $this->beforAction($controller);
                $controller->execute($this->httpContext, $this->viewEngine); 
                $this->afterAction($controller);
                return true;
            }
        }
        throw new HttpException('Unable to dispatch a controller. None of the registered routes matched the request URI.');
    }
    
    public function error(\Exception $e){
        $this->exceptionHandler->write($e);
    }
    
    public function end(){
        $session = $this->httpContext->getSession();
        
        if($session->active()){
            $this->httpContext->getResponse()->getCookies()->add(new \System\Mvc\Http\HttpCookie($session->getName(), $session->getSessionId()));
            $session->write();
        }
        $this->httpContext->getResponse()->flush();
    }

    protected function beforAction(Controller $controller){}
    
    protected function afterAction(Controller $controller){}
}
