<?php

namespace System\Mvc\Routing;

class RouteData {
    
    protected $data;
    
    public function __construct($data){
        $this->data = $data;
    }
    
    public function getModule() : string{
        if(array_key_exists('module', $this->data)){
            return $this->data['module'];
        }
        return '';
    }
    
    public function getController() : string{
        if(array_key_exists('controller', $this->data)){
            return $this->data['controller'];
        }
        return '';
    }
    
    public function getAction() : string{
        if(array_key_exists('action', $this->data)){
            return $this->data['action'];
        }
        return '';
    }
    
    public function get($name) : string{
        if(array_key_exists($name, $this->data)){
            return $this->data[$name];
        }
        return '';
    }
    
    public function toArray() : array{
        return $this->data;
    }
}
