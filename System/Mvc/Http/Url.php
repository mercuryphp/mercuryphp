<?php

namespace System\Mvc\Http;

class Url {
    
    private $httpScheme;
    private $host;
    private $rawUri;
    private $uri;

    public function __construct(){
        $this->httpScheme = $_SERVER['REQUEST_SCHEME'];
        $this->host = $_SERVER['HTTP_HOST'];
        $this->rawUri = trim($_SERVER['REQUEST_URI'], '/');
        $this->uri = \System\Core\Str::set($this->rawUri)->getIndexOf('?');
    }
    
    public function getHttpScheme() : string{
        return $this->httpScheme;
    }
    
    public function getHost() : string{
        return $this->host;
    }
    
    public function getRawUri() : string{
        return $this->rawUri;
    }
    
    public function getUri() : string{
        return $this->uri;
    }
}