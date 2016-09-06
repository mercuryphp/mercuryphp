<?php

namespace System\Web;

class HttpRequest {
    
    public function __construct() {
        $this->server = new \System\Collections\Dictionary($_SERVER);
        print_R($this->server);
    }
}