<?php

namespace System\Data\Entity;

class EntityContextCollection {
    
    protected $collection = [];
    
    public function add(EntityContext $entityContext){
        $this->collection[$entityContext->getHashCode()] = $entityContext;
    }
    
    public function hasEntity($entity){
        $hash = spl_object_hash($entity);
        if(array_key_exists($hash, $this->collection)){
            return true;
        }
        return false;
    }
    
    public function get($entity){
        $hash = spl_object_hash($entity);
        if(array_key_exists($hash, $this->collection)){
            return $this->collection[$hash];
        }
    }
}
