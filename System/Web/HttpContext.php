<?php

namespace System\Web;

final class HttpContext {
    
    private $httpRequest;
    
    public function __construct(HttpRequest $httpRequest) {
        $this->httpRequest = $httpRequest;
    }
    
    /**
     * Gets the HttpRequest object for the current request.
     * 
     * @return  System.Web.HttpRequest
     */
    public function getRequest() : HttpRequest {
        return $this->httpRequest;
    }
}

