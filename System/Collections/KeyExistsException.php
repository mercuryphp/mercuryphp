<?php

namespace System\Collections;

class KeyExistsException extends \Exception {
    public function __construct(string $key){
        parent::__construct(sprintf("An item in the collection with key '%s' already exists.", $key));
    }
}
