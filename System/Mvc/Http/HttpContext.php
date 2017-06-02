<?php

namespace System\Mvc\Http;

final class HttpContext {
    
    private $environment;
    private $request;
    private $response;
    private $session;

    public function __construct(\System\Core\Environment $environment, Request $request, Response $response, Session\Session $session){
        $this->environment = $environment;
        $this->request = $request;
        $this->response = $response;
        $this->setSession($session);
    }
    
    public function getEnvironment(){
        return $this->environment;
    }
    
    public function getRequest(){
        return $this->request;
    }
    
    public function getResponse(){
        return $this->response;
    }
    
    public function setSession(Session\Session $session){
        $this->session = $session;
        if($this->request->getCookie()->hasCookie($session->getName())){
            $session->setSessionId($this->request->getCookie($session->getName())->getValue());
        } 
    }
    
    public function getSession() : Session\Session{
        return $this->session;
    }
}
