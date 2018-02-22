<?php

namespace System\Mvc\Http;

class Url {
    
    private $httpScheme;
    private $host;
    private $rawUri = '';
    private $uri = '';
    private $url;
    private $baseUrl;

    public function __construct(){
        $this->httpScheme = $_SERVER['REQUEST_SCHEME'];
        $this->host = $_SERVER['HTTP_HOST'];
        $this->rawUri = trim($_SERVER['REQUEST_URI'], '/');
        
        if($this->rawUri && strpos($this->rawUri, '?') > -1){
            $this->uri = substr($this->rawUri, 0, strpos($this->rawUri, '?'));
        }else{
            $this->uri = $this->rawUri;
        }

        $port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
        $this->baseUrl = $this->httpScheme . '://' . $this->host . $port;
        $this->url = $this->httpScheme . '://' . $this->host . $port . '/' . $this->rawUri;
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
    
    public function getBaseUrl() : string{
        return $this->baseUrl;
    }
    
    public function getSegments(){
        return \System\Core\Arr::split('/', $this->uri);
    }
    
    public function __toString(){
        return $this->url;
    }
}