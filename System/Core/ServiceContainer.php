<?php

namespace System\Core;

class ServiceContainer {
    
    protected $services;
    
    public function __construct(array $services = []){
        $this->services = new Arr($services);
    }
    
    public function addService(Service $service){
        $this->services->add($service, $service->getName());
    }


    public function get(string $service){
        $service = $this->services->get($service);
        
        $args = $service->getArgs();
        
        foreach($args as $idx=>$arg){
            if(Str::set($arg)->first()->equals('@')){
                $refService = Str::set($arg)->subString(1);
                $args[$idx] = $this->get($refService);
            }
        }
        
        $serviceObj = Obj::getInstance($service->getClass(), $args);
        
        return $serviceObj;
    }
}