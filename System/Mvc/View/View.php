<?php

namespace System\Mvc\View;

abstract class View {
    
    protected $methods = [];
    
    public function addMethod(string $name, $class){
        $this->methods[$name] = $class;
    }
    
    public function getMethods() : array{
        return $this->methods;
    }
        
    public abstract function render(\System\Mvc\Http\HttpContext $httpContext, array $params = [], string $viewName) : string;
    
    public function __call($name, $arguments){
        if(is_string($this->methods[$name])){
            $object = \System\Core\Obj::getInstance($this->methods[$name]);
            $this->methods[$name] = $object;
        }else{
            $object = $this->methods[$name];
        }
        return $object->getClosure()->call($this, ...$arguments);
    }
}
