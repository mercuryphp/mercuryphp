<?php

namespace System\Diagnostics;

abstract class TraceListener {
    
    protected $data = [];
    
    public final function setData(array $data){
        $this->data =  $data;
    }
    
    public function write();
}