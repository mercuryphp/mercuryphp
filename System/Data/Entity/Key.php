<?php

namespace System\Data\Entity;

class Key {
    
    private $key;
    
    public function __construct($key){
        $this->key = $key;
    }
    
    public function getKey(){
        return $this->key;
    }
}
