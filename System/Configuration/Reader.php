<?php

namespace System\Configuration;

abstract class Reader {
    
    protected $configFile;
    
    public abstract function read() : \System\Collections\Dictionary;
    
    public function hasFile(){
        if(is_file($this->configFile)){
            return true;
        }
        return false;
    }
}