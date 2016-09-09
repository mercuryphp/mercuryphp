<?php

namespace System\Web;

final class HttpRequest {
    
    private $uri;
    private $server;
    private $routeData;
    
    public function __construct() {
        $this->server = new \System\Collections\Dictionary($_SERVER);
        $this->routeData = new \System\Collections\Dictionary();
        
        $this->uri = $this->server->getString('REQUEST_URI')->getLastIndexOf('?')->trim('/');
    }
    
    /**
     * Gets the request URI without query variables.
     * 
     * @return  System.Collections.Dictionary
     */
    public function getRouteData() : \System\Collections\Dictionary {
        return $this->routeData;
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