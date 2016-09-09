<?php

namespace System\Web;

class HttpResponseOutput {
    
    protected $output = [];
    
    public function write($string){
        $this->output[] = $string;
    }
}
