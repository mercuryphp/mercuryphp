<?php

namespace System\Data\Entity;

class EntityContext {
    
    protected $entity;
    protected $name;
    protected $hash;
    protected $state;
    
    const INSERT = 1;
    const UPDATE = 2;

    public function __construct($entity, int $state){
        $this->entity = $entity;
        $this->name = str_replace("\\", ".", get_class($entity));
        $this->hash = spl_object_hash($entity);
        $this->state = $state;
    }
    
    public function getName(){
        return $this->name;
    }

    public function getEntity(){
        return $this->entity;
    }
    
    public function getHash(){
        return $this->hash;
    }
    
    public function setState($state){
        $this->state = $state;
        return $this;
    }
    
    public function getState(){
        return $this->state;
    }
}