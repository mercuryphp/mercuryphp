<?php

namespace System\Data\Entity;

class EntityContext {
    
    protected $entityHash;
    protected $entityName;
    protected $entity;
    protected $state = 1;

    const PERSIST = 1;
    const PERSISTED = 2;
    const DELETE = 3;
    
    public function __construct($entity){
        $this->entityHash = spl_object_hash($entity);
        $this->entityName = get_class($entity);
        $this->entity = $entity;
    }
    
    /**
     * Gets the entity object stored in the context.
     */
    public function getEntity(){
        return $this->entity;
    }
    
    /**
     * Sets the entity state.
     */
    public function setState(int $state){
        $this->state = $state;
    }

    /**
     * Gets the entity state.
     */
    public function getState() : int {
        return $this->state;
    }
    
    /**
     * Gets the entity type name.
     */
    public function getEntityName() : string {
        return $this->entityName;
    }
    
    /**
     * Gets a unique hash code of the entity stored in the context.
     */
    public function getHashCode() : string {
        return $this->entityHash;
    }
}