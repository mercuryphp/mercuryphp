<?php

namespace System\Diagnostics;

abstract class TraceListener {
    
    protected $name;
    protected $data = [];
    
    public function setName(string $name){
        $this->name;
    }
    
    public function getName() : string {
        return $this->name;
    }

    public final function setData(array $data){
        $this->data =  $data;
    }
    
    public abstract function write();
}