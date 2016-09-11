<?php

namespace System\Web\Http;

class HttpResponse {
    
    protected $contentType;
    protected $encoding;
    protected $contentLength = 0;
    protected $output;
    
    public function __construct() {
        $this->output = new HttpResponseOutput();
    }
    
    public function setContentType(string $contentType){
        $this->contentType = $contentType;
        return $this;
    }
    
    public function getContentType() : string {
        return $this->contentType;
    }
    
    public function setContentEncoding(string $encoding){
        $this->encoding = $encoding;
        return $this;
    }
    
    public function getContentEncoding() : string {
        return $this->encoding;
    }
    
    public function setContentLength(int $length){
        $this->contentLength = $length;
        return $this;
    }
    
    public function getContentLength() : int {
        return $this->contentLength;
    }

    public function getOutput() : HttpResponseOutput {
        return $this->output;
    }
    
    public function flush(){
        echo $this->output->getBody();
    }
}
