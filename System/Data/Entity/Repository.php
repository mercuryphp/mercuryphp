<?php

namespace System\Data\Entity;

abstract class Repository{
    
    private $db;
    
    public function __construct(DbContext $db){
        $this->db = $db;
    }
    
    public function getDb(){
        return $this->db;
    }
}

