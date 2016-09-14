<?php

namespace System\Web\Http;

final class HttpRequest {
    
    private $uri;
    private $uriSegments;
    private $server;
    private $routeData;
    
    public function __construct() {
        $this->server = new \System\Collections\Dictionary($_SERVER);
        $this->routeData = new \System\Collections\Dictionary();
        
        $this->uri = $this->server->getString('REQUEST_URI')->getLastIndexOf('?')->trim('/');
        $this->uriSegments = $this->uri->split('\/');
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
    
    /**
     * Gets a System.Collections.ArrayList of uri segments.
     * 
     * @return  System.Collections.ArrayList
     */
    public function getUriSegments() : \System\Collections\ArrayList {
        return $this->uriSegments;
    }
}