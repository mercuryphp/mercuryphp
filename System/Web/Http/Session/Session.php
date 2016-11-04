<?php

namespace System\Web\Http\Session;

final class Session {
    
    private $httpRequest;
    private $httpResponse;
    private $sessionHandler;
    private $isStarted = false;
    private $sessionName;
    private $expires = 0;
    private $path = '/';
    private $domain = '';
    private $isSecure = false;
    private $isHttpOnly = true;
    private $sessionId = null;
    private $sessionData;
    
    public function __construct(\System\Web\Http\HttpRequest $httpRequest, \System\Web\Http\HttpResponse $httpResponse){
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
        $this->sessionData = new SessionData();
        $this->sessionName = 'X'.session_name();
    }
    
    public function getSessionId(){
        return $this->sessionId;
    }
    
    public function setHandler($handler){
        $this->sessionHandler = $handler;
    }

    public function setName(string $name){
        $this->sessionName = $name;
    }
    
    public function getName() : string {
        return $this->sessionName;
    }
    
    public function setExpires($expires){
        $this->expires = $expires;
    }
    
    public function getExpires(){
        return $this->expires;
    }
    
    public function setPath(string $path){
        $this->path = $path;
    }
    
    public function getPath() : string {
        return $this->path;
    }
    
    public function setDomain(string $domain){
        $this->domain = $domain;
    }
    
    public function getDomain() : string {
        return $this->domain;
    }
    
    public function isSecure(bool $bool = null){
        if(null === $bool){
            return $this->isSecure;
        }
        $this->isSecure = $bool;
    }

    public function isHttpOnly(bool $bool = null){
        if(null === $bool){
            return $this->isHttpOnly;
        }
        $this->isHttpOnly = $bool;
    }

    public function set(string $name, $data){
        $this->load();
        $this->sessionData->set($name, $data);
    }
    
    public function get(string $name, $default = null){
        $this->load();
        return $this->sessionData->get($name, $default);
    }
    
    public function remove(string $name){
        $this->load();
        $this->sessionData->remove($name);
    }

    private function load(){
        if(false === $this->isStarted){
            
            if($this->httpRequest->getCookies()->hasKey($this->sessionName)){
                $this->sessionId = $this->httpRequest->getCookies($this->sessionName)->getValue(); 
            }else{
                $this->sessionId = bin2hex(random_bytes(20));
                $this->httpResponse->getCookies()->add(new \System\Web\Http\HttpCookie(
                    $this->sessionName, 
                    $this->sessionId, 
                    $this->expires, 
                    $this->path, 
                    $this->domain, 
                    $this->isSecure, 
                    $this->isHttpOnly
                ));
            }
            
            $this->sessionData = $this->sessionHandler->read($this->sessionId);
            $this->isStarted = true;
        }
    }
    
    public function save(){
        if($this->isStarted){
            $this->sessionHandler->write($this->sessionId, $this->sessionData);
        }
    }
}
