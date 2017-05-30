<?php

namespace System\Mvc\Http\Session; 

abstract class Session {
    
    protected $collection = [];
    protected $name;
    protected $sessionId;
    protected $active = false;
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setSessionId($sessionId){
        $this->sessionId = $sessionId;
    }
    
    public function getSessionId(){
        return $this->sessionId;
    }
    
    public function active(){
        return $this->active;
    }

    public function set($name, $value){
        if(!$this->active){
            $this->open();
        }
        $this->collection[$name] = $value;
    }
    
    public function get($name, $default = null){
        if(!$this->active){
            $this->open();
        }
        if($this->exists($name)){
            return $this->collection[$name];
        }
        if(null !== $default){
            return $default;
        }
    }
    
    public function exists($name){
        if(array_key_exists($name, $this->collection)){
            return true;
        }
        return false;
    }
    
    public function start(){
        $this->active = true;
        $this->write();
    }
    
    public abstract function open();
    
    public abstract function write();
}