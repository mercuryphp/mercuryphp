<?php

namespace System\Data\Entity\Mapping\Attributes;

class Key {
    
    protected $keyName;
    
    public function __construct(string $keyName){
        $this->keyName = $keyName;
    }
    
    public function getName(){
        return $this->keyName;
    }
}