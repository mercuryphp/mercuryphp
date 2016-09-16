<?php

namespace System\Collections;

/**
 * This class represents an index based collection of elements.
 */
class ArrayList extends Collection {

    /**
     * Adds an element to the collection.
     */
    public function add($value) : ArrayList {
        $this->collection[] = $value;
        return $this;
    }
}
