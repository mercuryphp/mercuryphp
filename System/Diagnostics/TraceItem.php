<?php

namespace System\Diagnostics;

final class TraceItem {
    
    private $message;
    private $category;
    private $startTime;
    private $lastStartTime;

    public function __construct(string $message, $category, float $startTime){
        $this->message = $message;
        $this->category = $category;
        $this->startTime = $startTime;
    }
    
    public function getMessage() : string {
        return $this->message;
    }
    
    public function getCategory() : string {
        return $this->category;
    }
    
    public function getStartTime() : float {
        return $this->startTime;
    }
}

