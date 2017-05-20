<?php

namespace System\Core;

class Service {
    
    protected $name;
    protected $class;
    protected $constrArgs;
    
    public function __construct(string $name, string $class, $constrArgs = []){
        $this->name = $name;
        $this->class = $class;
        $this->constrArgs = $constrArgs;
    }
    
    public function getName() : string{
        return $this->name;
    }
    
    public function getClass(){
        return $this->class;
    }
    
    public function getArgs(){
        return $this->constrArgs;
    }
}