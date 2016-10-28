<?php

namespace System\Data\Entity;

class RandomString extends DefaultValue {
    
    public function __construct($byteLength){
        parent::__construct($byteLength);
    }
    
    public function getValue(){
        return strtoupper(bin2hex(random_bytes($this->value)));
    }
}