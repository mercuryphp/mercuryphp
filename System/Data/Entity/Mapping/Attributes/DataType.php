<?php

namespace System\Data\Entity\Mapping\Attributes;

class DataType {
    
    protected $dataType;
    
    public function __construct(string $dataType){
        $this->dataType = $dataType;
    }
    
    public function getType(){
        return $this->dataType;
    }
}