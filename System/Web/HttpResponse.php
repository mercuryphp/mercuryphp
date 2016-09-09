<?php

namespace System\Web;

class HttpResponse {
    
    public function __construct() {
        $this->output = new HttpResponseOutput();
    }
    
    public function getOutput() : HttpResponseOutput {
        return $this->output;
    }
    
    public function flush(){
        echo $this->output->getBody();
    }
}
