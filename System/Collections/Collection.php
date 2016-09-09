<?php

namespace System\Collections;

abstract class Collection implements \IteratorAggregate, \ArrayAccess, \Countable {
    
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
    
    /**
     * Merges an array or an instance of System.Collections.Collection with the collection.
     * 
     * @param   mixed $array
     * @return  @this
     */
    public function merge($array) : Collection {
        if($array instanceof \System\Collections\Collection){
            $array = $array->toArray();
        }
        $this->collection = array_merge($this->collection, $array);
        return $this;
    }
    
    /**
     * Gets the internal PHP array.
     * 
     * @return  array
     */
    public function toArray() : array {
        return $this->collection;
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
    
    /**
     * Gets a boolean value indicating if the collection offset exists.
     * This method is not intended to be used directly.
     * 
     * @param   mixed $offset
     * @return  bool
     */
    public function offsetExists($offset){
        if (array_key_exists($offset, $this->collection)){
            return true;
        }
        return false;
    }
    
    /**
     * Gets an element from the collection using an offset.
     * This method is not intended to be used directly.
     * 
     * @param   mixed $offset
     * @return  mixed
     */
    public function offsetGet($offset){
        return $this->collection[$offset];
    }
    
    /**
     * Sets an element in the collection using an offset.
     * This method is not intended to be used directly.
     * 
     * @param   mixed $offset
     * @param   mixed $value
     * @return  mixed
     */
    public function offsetSet($offset, $value){
        $this->collection[$offset] = $value;
    }
    
    /**
     * Removes an element from the collection using an offset.
     * This method is not intended to be used directly.
     * 
     * @param   mixed $offset
     * @return  void
     */
    public function offsetUnset($offset){
        unset($this->collection[$offset]);
    }
    
    /**
     * Gets the number of elements in the collection.
     * 
     * @return  int
     */
    public function count() : int {
        return count($this->collection);
    }
    
}