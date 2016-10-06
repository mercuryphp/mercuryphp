<?php

namespace System\Configuration;

final class EmptyReader extends Reader {
    
    public function read() : array {
        return [];
    }
}