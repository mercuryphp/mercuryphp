<?php

namespace System\Collections;

abstract class Collection {
    
    protected $collection = [];
    
    public function __construct(array $collection = []){
        $this->collection = $collection;
    }
}