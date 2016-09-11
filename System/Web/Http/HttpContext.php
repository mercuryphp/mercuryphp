<?php

namespace System\Web\Http;

final class HttpContext {
    
    private $httpRequest;
    private $httpResponse;
    
    public function __construct(HttpRequest $httpRequest, HttpResponse $httpResponse) {
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
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
        return $this->httpResponse;
    } 
}

