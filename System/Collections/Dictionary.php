<?php

namespace System\Collections;

/**
 * This class represents a collection of key/value data elements.
 */
class Dictionary extends Collection {
    
    /**
     * Adds an element to the collection using a $key.
     * Throws KeyExistsException if the $key already exists.
     */
    public function add($key, $value) : Dictionary {
        if(!$this->hasKey($key)){
            $this->collection[$key] = $value;
        }else{
            throw new KeyExistsException($key);
        }
        return $this;
    }
    
    /**
     * Adds or replaces an element in the collection using a key.
     */
    public function set($key, $value) : Dictionary {
        $this->collection[$key] = $value;
        return $this;
    }
    
    /**
     * Gets an stdClass object of the collection.
     */
    public function toObject() : stdClass {
        return json_decode(json_encode($this->collection), false);
    }
    
    /**
     * Magic method. Dynamically sets an element. This method is an alias of the
     * set() method.
     */
    public function __set($key, $value){
        return $this->set($key, $value);
    }
    
    /**
     * Magic method. Dynamically gets an element from the collection. This 
     * method is an alias of the get() method.
     */
    public function __get($key){
        return $this->get($key);
    }
}