<?php

namespace System\Mvc;

class JsonResult implements IActionResult {
    
    protected $response;
    protected $data;
    protected $options;
    
    public function __construct(Http\Response $response, $data, int $options = null){
        $this->response = $response;
        $this->data = $data;
        $this->options = $options;
    }
    
    public function execute() : string{
        $jsonData = json_encode($this->data, $this->options); 
        $this->response
            ->setContentType('application/json')
            ->setContentLength(strlen($jsonData));
        return $jsonData;
    }
}