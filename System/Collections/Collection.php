<?php

namespace System\Collections;

/**
 * Abstract base class for all collection types.
 */
abstract class Collection implements \IteratorAggregate, \ArrayAccess, \Countable {
    
    protected $collection = [];
    
    /**
     * Initializes a new instance of @class. If $collection is specified
     * then initializes the collection with default elements.
     */
    public function __construct(array $collection = []){
        $this->collection = $collection;
    }
    
    /**
     * Gets the first element from the collection.
     */
    public function first(){
        $item = reset($this->collection);
        if(is_string($item)){
            return new \System\Core\Str($item);
        }
        return $item;
    }
    
    /**
     * Gets the last element from the collection.
     */
    public function last(){
        $item = end($this->collection);
        if(is_string($item)){
            return new \System\Core\Str($item);
        }
        return $item;
    }
    
    /**
     * Pop the element off the end of array.
     */
    public function pop() : Collection {
        array_pop($this->collection);
        return $this;
    }
    
    /**
     * Determines if the collection contains an element with the specified $key.
     */
    public function hasKey($key) : bool {
        if(array_key_exists($key, $this->collection)){
            return true;
        }
        return false;
    }
    
    /**
     * Determines if the collection contains an element with the specified $value.
     */
    public function contains($value) : bool {
        if(in_array($value, $this->collection)){
            return true;
        }
        return false;
    }
    
    /**
     * Gets an element from the collection using the specified $key. If $default 
     * is specified and the element is not found then gets $default. 
     */
    public function get($key, $default = null){
        if($this->hasKey($key)){
            return $this->collection[$key];
        }
        return $default;
    }
    
    /**
     * Removes an element from the collection using the specified $key. Numeric
     * keys are reindexed if $reindex is set to true. 
     */
    public function removeAt($key, $reindex = false) : bool {
        if($this->hasKey($key)){
            unset($this->collection[$key]);
            
            if($reindex){
                $this->collection = array_values($this->collection);
            }
            return true;
        }
        return false;
    }
    
    /**
     * Removes the first occurrence of an element from the collection.
     */
    public function remove($value) : bool{
        foreach($this->collection as $key=>$item){
            if($item == $value){
                unset($this->collection[$key]);
                return true;
            }
        }
        return false;
    }
    
   /**
     * Gets an element from the collection as an instance of System.Core.Str
     * using the specified $key. If $default is specified and the element is not 
     * found then gets $default.
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
     * Merges an array or an instance of System.Collections.Collection with 
     * this collection instance.
     */
    public function merge($array) : Collection {
        if($array instanceof \System\Collections\Collection){
            $array = $array->toArray();
        }
        $this->collection = array_merge($this->collection, $array);
        return $this;
    }
    
    /**
     * Reverses the order of the elements in the collection.
     */
    public function reverse() : Collection {
        $this->collection = array_reverse($this->collection);
        return $this;
    }
    
    /**
     * Sorts the elements in the collection.
     */
    public function sort() : Collection {
        asort($this->collection);
        return $this;
    }
    
    /**
     * Gets an ArrayList of all keys in the collection.
     */
    public function getKeys() : ArrayList {
        return new ArrayList(array_keys($this->collection));
    }
    
    /**
     * Applies a callback function to all elements in the collection.
     */
    public function each(callable $func) : Collection {
        foreach($this->collection as $k=>$v){
            $this->collection[$k] = $func($v, $k);
        }
        return $this;
    }
    
    /**
     * Gets the internal PHP array.
     */
    public function toArray() : array {
        return $this->collection;
    }
    
    /**
     * Gets an ArrayIterator object so that elements in this Collection instance 
     * can be iterated.
     */
    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
    
    /**
     * Gets a boolean value indicating if the collection offset exists.
     * This method is not intended to be used directly.
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
     */
    public function offsetGet($offset){
        return $this->collection[$offset];
    }
    
    /**
     * Sets an element in the collection using an offset.
     * This method is not intended to be used directly.
     */
    public function offsetSet($offset, $value){
        $this->collection[$offset] = $value;
    }
    
    /**
     * Removes an element from the collection using an offset.
     * This method is not intended to be used directly.
     */
    public function offsetUnset($offset){
        unset($this->collection[$offset]);
    }
    
    /**
     * Gets the number of elements in the collection.
     */
    public function count() : int {
        return count($this->collection);
    }
}