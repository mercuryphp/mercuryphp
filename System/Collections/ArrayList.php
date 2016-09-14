<?php

namespace System\Collections;

class ArrayList extends Collection {

    /**
     * Determines if the collection contains an element with the specified key.
     */
    public function add($value) : ArrayList {
        $this->collection[] = $value;
        return $this;
    }
}
