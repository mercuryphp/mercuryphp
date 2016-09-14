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
}