<?php

namespace System\Mvc;

use System\Core\Obj;
use System\Core\Str;
use System\Core\Arr;
use System\Core\ServiceContainer;

abstract class Controller {
    
    private $httpContext;
    private $registry;
    private $config;
    private $serviceContainer;
    private $view;
    
    public function __construct(){
        $this->registry = new Arr();
    }

    public function getHttpContext(){
        return $this->httpContext;
    }

    public function getEnvironment() : \System\Core\Environment{
        return $this->httpContext->getEnvironment();
    }
    
    public function getRequest() : Http\Request{
        return $this->httpContext->getRequest();
    }
    
    public function getResponse() : Http\Response{
        return $this->httpContext->getResponse();
    }
    
    public function getSession() : Http\Session\Session{
        return $this->httpContext->getSession();
    }
    
    public function getRegistry(){
        return $this->registry;
    }
    
    public function setServices(ServiceContainer $services){
        $this->serviceContainer = $services;
    }
    
    public function getServices() : ServiceContainer{
        return $this->serviceContainer;
    }

    public function setViewEngine(View\View $view){
        $this->view = $view;
    }

    public function getViewEngine() : View\View{
        return $this->view;
    }

    public function setConfiguration(\System\Core\Configuration $config){
        $this->config = $config;
    }
    
    public function getConfiguration() : \System\Core\Configuration{
        return $this->config;
    }

    protected function view(array $params = []) : IActionResult{
        return new ViewResult($this->view, $this->httpContext, $params);
    }
    
    protected function json(array $params, int $options = null) : IActionResult{
        return new JsonResult($this->getResponse(), $params, $options);
    }
    
    protected function redirect(string $location) : IActionResult{
        return new RedirectResult($this->getHttpContext(), $location);
    }
    
    protected function load(){}
    
    protected function unload(){}
    
    protected function render(string $output){
        $this->httpContext->getResponse()->write($output);
    }

    public final function execute(Http\HttpContext $httpContext, View\View $view = null){

        $this->httpContext = $httpContext;
        $this->view = $view;

        $routeData = $httpContext->getRequest()->getRouteData();
        
        if(!Obj::hasMethod($this, $routeData->getAction())){
            throw new HttpException('Action ' . get_class($this) . ':' . $routeData->getAction() . '() does not exist.');
        }

        $moduleFile = (string)Str::set(Obj::getFileName($this))->getLastIndexOf('/')->append('/Module.php');
        $moduleName = (string)Str::set(get_class($this))->getLastIndexOf('\\')->append('\Module');

        if(is_file($moduleFile)){
            try{
                $moduleInstance = Obj::getInstance($moduleName);
            } catch (\Exception $e){
                throw new HttpException($e->getMessage());
            }

            if(!$moduleInstance instanceof Module){
                throw new HttpException(sprintf("Module '%s' must inherit from System\Mvc\Module.", $moduleName));
            }
            $moduleInstance->load($this);
        }else{
            $moduleInstance = null;
        }

        $this->load();
        
        $attributes = Obj::getMethodAttributes($this, $routeData->getAction(), false);

        foreach($attributes as $attribute => $args){
            $attributeInstance = Obj::getInstance($attribute);
            $ref = new \ReflectionMethod($attribute, 'execute');
            $attributeClosure = $ref->getClosure($attributeInstance)->bindTo($this);
            $result = $attributeClosure(...$args);

            if(!$result){ return; }
        }

        $result = Obj::invokeMethod($this, $routeData->getAction(), $httpContext->getRequest()->getParam());

        if(!$result instanceof IActionResult){
            if(is_scalar($result) || is_null($result)){
                $result = new StringResult($result);
            }else{
                $result = new JsonResult($this->httpContext->getResponse(), $result);
            }
        }
        
        $this->unload();

        $this->render($result->execute());

        if(null !== $moduleInstance){
            $moduleInstance->unload($this);
        }
    }
    
    public function __get($name){
        if($this->registry->hasKey($name)){
            return $this->registry->get($name);
        }
        throw new \RuntimeException(sprintf("Controller registry does not contain property '%s'", $name));
    }
}
