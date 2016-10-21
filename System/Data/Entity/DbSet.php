<?php

namespace System\Data\Entity;

class DbSet {
    
    protected $entityName;
    protected $entities = [];
    
    public function __construct($entityName){
        $this->entityName = str_replace('.', '\\',$entityName);
    }
    
    public function add($entity){
        if(get_class($entity) != $this->entityName){
            print "error";
        }
        $this->entities[] = new EntityContext($entity);
    }
}