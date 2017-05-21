<?php

namespace System\Data\Entity\Mapping\Driver;

use System\Data\Entity\Mapping\EntityAttributeData;

class AnnotationDriver {
    
    public function read($entityName) : EntityAttributeData{
        $className = str_replace(".", "\\", $entityName);
        $data = \System\Core\Obj::getClassAttributes($className);
        $properties = array_keys(\System\Core\Obj::getProperties($className));
                
        foreach($properties as $property){
            $attributes = \System\Core\Obj::getPropertyAttributes($className, $property);
            $data['fields'][$property] = $attributes;
        }
        
        return new EntityAttributeData($entityName,$data);
    }
}

