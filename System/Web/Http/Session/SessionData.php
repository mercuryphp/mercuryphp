<?php

namespace System\Web\Http\Session;

class SessionData {
    
    private $data = [];

    public function set($name, $data){
        $this->data[$name] = $data;
    }
    
    public function get($name, $default = null){
        if($this->hasKey($name)){
            return $this->data[$name];
        }
        return $default;
    }
    
    public function remove($name){
        if($this->hasKey($name)){
            return $this->data[$name];
        }
    }
    
    public function hasKey($name){
        if(array_key_exists($name, $this->data)){
            return true;
        }
        return false;
    }
}