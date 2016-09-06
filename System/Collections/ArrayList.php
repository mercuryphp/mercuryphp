<?php

namespace System\Collections;

class ArrayList extends Collection {

    /**
     * Adds an element to the end of the collection.
     */
    public function add($value) : ArrayList {
        $this->collection[] = $value;
        return $this;
    }
}
