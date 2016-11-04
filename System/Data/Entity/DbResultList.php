<?php

namespace System\Data\Entity;

class DbResultList implements \IteratorAggregate {
    
    protected $result;
    
    public function __construct(array $result){
        $this->result = $result;
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->result);
    }
}