<?php

namespace System\Web\Mvc;

use System\Core\Obj;
use System\Core\Attribute;
use System\Diagnostics\Trace;
use System\Collections\Dictionary;

abstract class Controller {
    
    private $httpContext;
    private $viewEngine;
    private $registry;

    public function __construct(){
        $this->viewEngine = new ViewEngine\NativeView();
        $this->registry = new Dictionary();
    }
    
    public function getHttpContext() : \System\Web\Http\HttpContext {
        return $this->httpContext;
    }
    
    public function getRequest() : \System\Web\Http\HttpRequest {
        return $this->httpContext->getRequest();
    }
    
    public function getResponse() : \System\Web\Http\HttpResponse {
        return $this->httpContext->getResponse();
    }
    
    public function getSession() : \System\Web\Http\Session\Session {
        return $this->httpContext->getSession();
    }
    
    public function getRegistry() : Dictionary {
        return $this->registry;
    }

    public function view(array $data = [], string $viewName = ''){
        $viewResult = new ViewResult($this->getViewEngine(), new ViewContext($this->httpContext, $data, $viewName));
        return $viewResult;
    }
    
    public function json($data = [], $options = null){
        $jsonResult = new JsonResult($this->httpContext->getResponse(), $data, $options);
        return $jsonResult;
    }
    
    public function setViewEngine(ViewEngine\IView $viewEngine) : Controller {
        $this->viewEngine = $viewEngine;
        $this->viewEngine->setPath($this->rootPath);
        return $this;
    }
    
    public function getViewEngine() : ViewEngine\IView {
        return $this->viewEngine;
    }
    
    public function load(){}
    
    public function execute(\System\Web\Http\HttpContext $httpContext){

        $this->httpContext = $httpContext;

        $refClass = new \ReflectionClass($this);
        $actionName = $this->httpContext->getRequest()->getRouteData()->get('action');

        if(!$refClass->hasMethod($actionName)){
            throw new ActionNotFoundException($this->httpContext, get_class($this));
        }
        
        $moduleFile = \System\Core\Str::set($refClass->getFileName())->replace("\\\\",'/')->getLastIndexOf('/')->append('/Module.php');
        $module = null;

        if(is_file($moduleFile)){
            $moduleClass = (string)\System\Core\Str::set(get_class($this))->getLastIndexOf('\\')->append('\Module');
            $module = new $moduleClass();

            if(!$module instanceof HttpModule){
                throw new HttpException(sprintf("The module '%s' does not inherit from System.Web.Mvc.HttpModule.", $moduleClass));
            }
            
            Trace::write('Module load()');
            $module->load($this);
        }
        
        Trace::write('Controller load()');
        $this->load();
        
        $attributes = Attribute::getAttributes($this, $actionName);
        
        foreach($attributes as $idx=>$attribute){
            if($attribute instanceof FilterAttribute && !$attribute->isValid($this)){
                return false;
            }
        }
        
        $actionMethod = $refClass->getMethod($actionName);

        $actionParameters = $actionMethod->getParameters();
        $actionArgs = [];

        foreach($actionParameters as $param){
            $defaultValue = $param->isOptional() ? $param->getDefaultValue() : null;
            if(is_object($param->getClass())){
                $actionArgs[] = (new ObjectModelBinder())->bind($param->getType(), $this->httpContext->getRequest(), $param->name);
            }else{
                switch($param->getType()){
                    case 'array':
                        $actionArgs[] = $this->httpContext->getRequest()->getParams()->toArray();
                        break;

                    default:
                        if("int" == $param->getType()){
                            $filter = FILTER_VALIDATE_INT;
                        }
                        else{
                            $filter = FILTER_UNSAFE_RAW;
                        }

                        $value = filter_var($this->httpContext->getRequest()->getParams($param->name, $defaultValue), $filter); 

                        if(false === $value){
                            print "its false";exit;
                        }

                        $actionArgs[] = $value;
                        break;
                }
            }
        }

        Trace::write('Controller action()');
        $actionResult = $actionMethod->invokeArgs($this, $actionArgs);

        if(!$actionResult instanceof \System\Web\Mvc\IActionResult){
            if(is_array($actionResult)){
                $actionResult = new \System\Web\Mvc\JsonResult($this->httpContext->getResponse(), $actionResult, null);
            }else{
                $actionResult = new \System\Web\Mvc\StringResult($actionResult);
            }
        }
        
        Trace::write('Controller render()');
        $this->render($actionResult);
        
        if($module instanceof HttpModule){
            Trace::write('Module unload()');
            $module->unload($this);
        }
    }
    
    public function render(IActionResult $actionResult){
        $this->httpContext->getResponse()->getOutput()->write($actionResult->execute());
    }
    
    public function __get($name) {
        return $this->registry->get($name);
    }
} 
