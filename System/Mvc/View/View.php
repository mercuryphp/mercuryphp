<?php

namespace System\Mvc\View;

abstract class View {
    
    protected $methods = [];
    protected $params = [];
    
    public function addMethod(string $name, $class){
        $this->methods[$name] = $class;
    }
    
    public function getMethods() : array{
        return $this->methods;
    }
    
    public function addParam(string $name, $value){
        $this->params[$name] = $value;
    }
        
    public abstract function render(\System\Mvc\Http\HttpContext $httpContext, array $params = [], string $viewName = '') : string;
    
    public function __call($name, $arguments){
        if(is_string($this->methods[$name])){
            $object = \System\Core\Obj::getInstance($this->methods[$name]);
            $this->methods[$name] = $object;
        }else{
            $object = $this->methods[$name];
        }

        $refMethod = new \ReflectionMethod($object, 'execute');
        return $refMethod->invokeArgs($object, $arguments);
    }
    
    public function __get($name){
        if(array_key_exists($name, $this->params)){
            return $this->params[$name];
        }
    }
}
