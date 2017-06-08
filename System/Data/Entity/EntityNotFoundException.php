<?php

namespace System\Data\Entity;

class EntityNotFoundException extends \Exception {
    
    protected $entityName;
    protected $data;
    
    public function __construct(string $entityName, array $data = []){
        parent::__construct(sprintf("The entity '%s' does not exist.", $entityName));
        $this->entityName = $entityName;
        $this->data = $data;
    }
    
    public function getEntityName(){
        return $this->entityName;
    }
    
    public function getData(){
        return $this->data;
    }
}

