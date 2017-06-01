<?php

namespace System\Core;

class ServiceContainer {
    
    protected $services;
    protected $cached;
    
    public function __construct(array $services = []){
        $this->services = new Arr($services);
        $this->cached= new Arr();
    }
    
    public function addService(Service $service){
        $this->services->add($service->getName(), $service);
    }


    public function get(string $serviceName){
        
        if($this->cached->hasKey($serviceName)){
            return $this->cached->get($serviceName);
        }
        
        $service = $this->services->get($serviceName);
        
        $args = $service->getArgs();
        
        foreach($args as $idx=>$arg){
            if(Str::set($arg)->first()->equals('@')){
                $refService = Str::set($arg)->subString(1);
                $args[$idx] = $this->get($refService);
            }
        }
        
        $serviceObj = Obj::getInstance($service->getClass(), $args);
        $this->cached->add($serviceName, $serviceObj);
        return $serviceObj;
    }
}