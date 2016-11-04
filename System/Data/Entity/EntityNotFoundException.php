<?php

namespace System\Data\Entity;

class EntityNotFoundException extends \Exception {
    
    protected $entityName;
    
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, $entityName) {
        parent::__construct($message, $code, $previous);
        $this->entityName = $entityName;
    }
    
    public function getEntityName(){
        return $this->entityName;
    }
}