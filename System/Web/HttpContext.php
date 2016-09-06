<?php

namespace System\Web;

final class HttpContext {
    
    private $httpRequest;
    
    public function __construct(HttpRequest $httpRequest) {
        $this->httpRequest = $httpRequest;
    }
}

