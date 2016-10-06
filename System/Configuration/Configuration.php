<?php

namespace System\Configuration;

final class Configuration {

    private $config = [];
    
    public function __construct(Reader $configReader){
        $this->config = $configReader->read();
    }
    
    public function hasPath($path){
        if($this->config->hasKey($path)){
            return true;
        }
        return false;
    }

    public function get($path, $default = null) {
        if($this->config->hasKey($path)){
            return $this->config->get($path, $default);
        }
        return $default;
    }
    
    public function toArray(){
        return $this->config;
    }
}