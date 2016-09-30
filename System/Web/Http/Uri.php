<?php

namespace System\Web\Http;

class Uri {
    
    protected $scheme;
    protected $host;
    protected $port;
    protected $path;
    protected $pathSegments;
    protected $query;
    
    public function __construct(string $uri){
        
        $segments = parse_url($uri);
        $this->scheme = isset($segments['scheme']) ? $segments['scheme'] : 'http';
        $this->host = isset($segments['host']) ? $segments['host'] : '';
        $this->port = isset($segments['port']) ? $segments['port'] : 80;
        $this->path = isset($segments['path']) ? trim($segments['path'], '/') : '';
        $this->pathSegments = \System\Core\Str::set($this->path)->split('\/');
        $this->query = isset($segments['query']) ? $segments['query'] : '';
    }
    
    /**
     * Gets the Scheme for this Uri.
     */
    public function getScheme() : string {
        return $this->scheme;
    }
    
    /**
     * Gets the host for this Uri.
     */
    public function getHost() : string {
        return $this->host;
    }
    
    /**
     * Gets the port for this Uri.
     */
    public function getPort() : int {
        return $this->port;
    }
    
    /**
     * Gets the Uri's path.
     */
    public function getPath() : string {
        return $this->path;
    }
    
    /**
     * Gets the Uri's path query string.
     */
    public function getQuery() : string {
        return $this->query;
    }
    
    /**
     * Gets a System.Collections.ArrayList of uri path segments.
     */
    public function getPathSegments() : \System\Collections\ArrayList {
        return $this->pathSegments;
    }
}