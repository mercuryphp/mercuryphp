<?php

namespace System\Collections;

/**
 * Exception thrown when an item in the collection with the same key already exists.
 */
class KeyExistsException extends \Exception {
    
    /**
     * 
     */
    public function __construct(string $key){
        parent::__construct(sprintf("An item in the collection with key '%s' already exists.", $key));
    }
}