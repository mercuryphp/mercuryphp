<?php

namespace System\Data\Entity;

class DateTime extends DefaultValue {
    
    public function __construct($timezone = null){
        parent::__construct($timezone);
    }
    
    public function getValue(){
        return \System\Core\Date::now($this->value)->toString();
    }
}