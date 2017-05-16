<?php

namespace System\Data\Entity\Mapping\Driver;

use System\Data\Entity\Mapping\EntityAttributeData;

class JsonDriver {
    
    protected $path;
    
    public function __construct(string $path){
        $this->path = $path;
    }
    
    public function read($entityName) : EntityAttributeData{
        
        $fileName = $this->path . '/' . str_replace(".", "_", $entityName) . '.json';
        
        if(is_file($fileName)){
            $data = json_decode(file_get_contents($fileName), true);
            return new EntityAttributeData($data);
        }
    }
}

