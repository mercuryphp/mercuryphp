<?php

namespace System\Web\Http\Session;

final class Session {
    
    private $httpRequest;
    private $httpResponse;
    private $sessionHandler;
    private $isStarted = false;
    private $sessionName;
    private $sessionId = null;
    private $sessionData;
    
    public function __construct(\System\Web\Http\HttpRequest $httpRequest, \System\Web\Http\HttpResponse $httpResponse){
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
        $this->sessionData = new SessionData();
        $this->sessionName = 'X'.session_name();
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
    
    public function getSessionId(){
        return $this->sessionId;
    }

    public function set($name, $data){
        $this->load();
        $this->sessionData->set($name, $data);
    }
    
    public function get($name){
        $this->load();
        return $this->sessionData->get($name);
    }
    
    public function remove($name){
        $this->load();
        $this->sessionData->remove($name);
    }
    
    public function save(){
        if($this->isStarted){
            $this->sessionHandler->write($this->sessionId, $this->sessionData);
        }
    }
    
    private function load(){
        if(false === $this->isStarted){
            
            if($this->httpRequest->getCookies()->hasKey($this->sessionName)){
                $this->sessionId = $this->httpRequest->getCookies($this->sessionName)->getValue(); 
            }else{
                $this->sessionId = bin2hex(random_bytes(20));
                $this->httpResponse->getCookies($this->sessionName)->setValue($this->sessionId);
            }
            
            $this->sessionData = $this->sessionHandler->read($this->sessionId);
            $this->isStarted = true;
        }
    }
}
