<?php

namespace System\Data\Entity;

class DataType {
    
    private $dataType;
    
    public function __construct(string $dataType){
        $this->dataType = $dataType;
    }
    
    public function getDataType() : string {
        return $this->dataType;
    }
}

