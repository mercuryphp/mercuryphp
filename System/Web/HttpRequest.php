<?php

namespace System\Web;

class HttpRequest {
    
    protected $uri;
    
    public function __construct() {
        $this->server = new \System\Collections\Dictionary($_SERVER);
        
        $this->uri = $this->server->getString('REQUEST_URI')->getLastIndexOf('?')->trim('/');
    }
    
    /**
     * Gets the request URI without query variables.
     * 
     * @return  System.Core.Str
     */
    public function getUri() : \System\Core\Str {
        return $this->uri;
    }
}