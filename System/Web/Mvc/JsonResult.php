<?php

namespace System\Web\Mvc;

class JsonResult implements IActionResult {
    
    protected $httpResponse;
    protected $data;
    protected $options;
    
    public function __construct(\System\Web\Http\HttpResponse $httpResponse, $data, $options){
        $this->httpResponse = $httpResponse;
        $this->data = $data;
        $this->options = $options;
    }
    
    public function execute() : string {
        $jsonData = json_encode($this->data, $this->options); 
        $this->httpResponse
            ->setContentType('application/json')
            ->setContentEncoding('UTF-8')
            ->setContentLength(strlen($jsonData));
        return $jsonData;
    }
}