<?php

namespace System\Collections;

abstract class Collection implements \IteratorAggregate {
    
    protected $collection = [];
    
    public function __construct(array $collection = []){
        $this->collection = $collection;
    }
    
    /**
     * Determines if the collection contains an element with the specified key.
     * 
     * @param   mixed $key
     * @return  bool
     */
    public function hasKey($key) : bool {
        if(array_key_exists($key, $this->collection)){
            return true;
        }
        return false;
    }
    
    /**
     * Gets an element from the collection using the specified $key. If $default 
     * is specified and the element is not found then gets $default. 
     * 
     * @param   mixed $key
     * @param   mixed $default = null
     * @return  mixed
     */
    public function get($key, $default = null){
        if($this->hasKey($key)){
            return $this->collection[$key];
        }
        return $default;
    }
    
   /**
     * Gets an element from the collection as an instance of System.Core.Str
     * using the specified $key. If $default is specified and the element is not 
     * found then gets $default.
     * 
     * @param   mixed $key
     * @param   string $default = null
     * @return  System.Core.Str
     */
    public function getString($key, $default = null) : \System\Core\Str {
        if($this->hasKey($key)){
            $default = $this->collection[$key];
        }
        if(is_string($default)){
            return \System\Core\Str::set($default);
        }
        return new \System\Core\Str();
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
}