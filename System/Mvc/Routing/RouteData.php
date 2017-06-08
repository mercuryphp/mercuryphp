<?php

namespace System\Mvc\Routing;

class RouteData {
    
    protected $namespace;
    protected $module;
    protected $controller;
    protected $action;
    protected $data = [];

    public function __construct(\System\Core\Arr $data){
        $this->namespace = $data->get('namespace');
        $this->module = $data->get('module');
        $this->controller = $data->get('controller');
        $this->action = $data->get('action');
        $this->data = $data->toArray();
    }
    
    public function setNamespace(string $namespace){
        $this->namespace = $namespace;
    }
    
    public function getNamespace() : string{
        return $this->namespace;
    }
    
    public function getModule() : string{
        return $this->module;
    }
    
    public function getController() : string{
        return $this->controller;
    }
    
    public function getAction() : string{
        return $this->action;
    }
    
    public function toArray(){
        return $this->data;
    }
}
