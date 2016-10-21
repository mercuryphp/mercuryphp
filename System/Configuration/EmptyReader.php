<?php

namespace System\Configuration;

final class EmptyReader extends Reader {
    
    public function read() : \System\Collections\Dictionary {
        return new \System\Collections\Dictionary();
    }
}