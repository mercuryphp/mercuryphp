<?php

namespace System\Web;

final class HttpContext {
    
    private $httpRequest;
    private $response;
    
    public function __construct(HttpRequest $httpRequest, HttpResponse $response) {
        $this->httpRequest = $httpRequest;
        $this->response = $response;
    }
    
    /**
     * Gets the HttpRequest object for the current request.
     * 
     * @return  System.Web.HttpRequest
     */
    public function getRequest() : HttpRequest {
        return $this->httpRequest;
    }
    
    /**
     * Gets the HttpResponse object for this context.
     * 
     * @return  System.Web.HttpResponse
     */
    public function getResponse() : HttpResponse{
        return $this->response;
    } 
}

