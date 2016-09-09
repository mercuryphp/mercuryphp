<?php

namespace System\Collections;

class Dictionary extends Collection {
    
    /**
     * Adds an element to the collection using a key.
     * Throws KeyExistsException if the key already exists.
     * 
     * @param   mixed $key
     * @param   mixed $value
     * @return  @this
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
     * 
     * @param   mixed $key
     * @param   mixed $value
     * @return  @this
     */
    public function set($key, $value) : Dictionary {
        $this->collection[$key] = $value;
        return $this;
    }
}