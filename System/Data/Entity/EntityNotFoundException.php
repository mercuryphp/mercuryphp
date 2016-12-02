<?php

namespace System\Data\Entity;

class EntityNotFoundException extends \Exception {
    
    protected $entityName;
    protected $data;
    
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, string $entityName, $data = null) {
        parent::__construct($message, $code, $previous);
        $this->entityName = $entityName;
        $this->data = $data;
    }
    
    public function getEntityName() : string {
        return $this->entityName;
    }
    
    public function getData(){
        return $this->data;
    }
}