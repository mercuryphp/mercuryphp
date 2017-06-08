<?php

namespace System\Mvc;

class RedirectResult implements IActionResult {
    
    protected $response;
    protected $location;
    protected $immediate;
    
    public function __construct(Http\Response $response, string $location, bool $immediate = false){
        $this->response = $response;
        $this->immediate = $immediate;
    }
    
    public function execute() : string{ 
        $this->response->redirect($this->location, $this->immediate);
        return '';
    }
}