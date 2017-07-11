<?php

namespace System\Mvc\Http;

class HttpFileCollection implements \IteratorAggregate{
    
    protected $collection = [];
    
    public function __construct($files){

        foreach($files as $name => $properties){
            $this->collection[$name] = new HttpFile($properties);
        }
    }
    
    public function get($name){
        if($this->hasFile($name)){
            return $this->collection[$name];
        }
    }

    public function hasFile($name) : bool{
        if(array_key_exists($name, $this->collection)){
            return true;
        }
        return false;
    }
    
    public function toArray() : array{
        return $this->collection;
    }

    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
}