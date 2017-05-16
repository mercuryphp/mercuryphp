<?php

namespace System\Data\Entity;

class DbConfiguration {
    
    protected $attributeDriver;
    
    public function __construct(){
        //$this->metadataDriver = new 
    }
    
    public function setEntityAttributeDriver($driver){
        $this->attributeDriver = $driver;
    }
    
    public function getEntityAttributeDriver(){
        return $this->attributeDriver;
    }
}