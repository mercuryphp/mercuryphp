<?php

namespace System\Web\Http;

final class HttpContext {
    
    private $httpRequest;
    private $httpResponse;
    private $session;


    public function __construct(HttpRequest $httpRequest, HttpResponse $httpResponse, Session\Session $session) {
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
        $this->session = $session;
    }
    
    /**
     * Gets the HttpRequest object for the current request.
     */
    public function getRequest() : HttpRequest {
        return $this->httpRequest;
    }
    
    /**
     * Gets the HttpResponse object for this context.
     */
    public function getResponse() : HttpResponse {
        return $this->httpResponse;
    } 
    
    /**
     * Gets the Session object for this context.
     */
    public function getSession() : Session\Session {
        return $this->session;
    } 
}

