<?php

namespace System\Data\Entity;

class DbResultList implements \IteratorAggregate, \ArrayAccess {
    
    protected $result;
    
    public function __construct(array $result){
        $this->result = $result;
    }
    
    public function rowKey($name){
        foreach($this->result as $idx => $item){
            if(is_object($item)){
                
            }
            
            if(array_key_exists($name, $item)){
                $value = $item[$name];
                unset($this->result[$idx]);
                $this->result[$value] = $item;
                
            }
        }
        return $this;
    }
    
    public function toArray(){
        return $this->result;
    }

    public function getIterator(){
        return new \ArrayIterator($this->result);
    }
    
    public function count(){
        return count($this->result);
    }


    /**
     * Gets a boolean value indicating if the collection offset exists.
     * This method is not intended to be used directly.
     */
    public function offsetExists($offset){
        if (array_key_exists($offset, $this->result)){
            return true;
        }
        return false;
    }
    
    /**
     * Gets an element from the collection using an offset.
     * This method is not intended to be used directly.
     */
    public function offsetGet($offset){
        return $this->result[$offset];
    }
    
    /**
     * Sets an element in the collection using an offset.
     * This method is not intended to be used directly.
     */
    public function offsetSet($offset, $value){
        $this->result[$offset] = $value;
    }
    
    /**
     * Removes an element from the collection using an offset.
     * This method is not intended to be used directly.
     */
    public function offsetUnset($offset){
        unset($this->result[$offset]);
    }
}