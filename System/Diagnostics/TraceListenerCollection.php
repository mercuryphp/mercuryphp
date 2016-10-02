<?php

namespace System\Diagnostics;


class TraceListenerCollection implements \IteratorAggregate, \Countable {
    
    protected $collection = [];
    
    public function add(TraceListener $listener){
        $this->collection[$listener->getName()] = $listener;
    }

    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
    
    public function count(){
        return count($this->collection);
    }
}