<?php

namespace System\Mvc;

class RedirectResult implements IActionResult {
    
    protected $response;
    protected $session;
    protected $location;
    protected $immediate;
    
    public function __construct(Http\HttpContext $httpContext, string $location, bool $immediate = false){
        $this->response = $httpContext->getResponse();
        $this->session = $httpContext->getSession();
        $this->location = $location;
        $this->immediate = $immediate;
    }
    
    public function with(string $string){
        $this->session->set('__FLASH_DATA__', $string);
        return $this;
    }
    
    public function execute() : string{ 
        $this->response->redirect($this->location, $this->immediate);
        return '';
    }
}