<?php

namespace System\Data\Entity;

class DbSet implements \IteratorAggregate{
    
    protected $db;
    protected $entities = [];
    
    public function __construct(DbContext $db){
        $this->db = $db;
    }
    
    public function add($entity){
        $this->entities[] = new EntityContext($entity,1);
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->entities);
    }
}