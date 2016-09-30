<?php

namespace System\Diagnostics;

final class TraceItem {
    
    private $message;
    private $category;
    private $startTime;
    private $httpMethod;

    public function __construct(string $message, $category, $startTime, $httpMethod){
        $this->message = $message;
        $this->category = $category;
        $this->startTime = $startTime;
        $this->httpMethod = $httpMethod;
    }
    
    public function getMessage(){
        return $this->message;
    }
}

