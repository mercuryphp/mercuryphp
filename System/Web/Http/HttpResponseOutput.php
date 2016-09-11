<?php

namespace System\Web\Http;

class HttpResponseOutput {
    
    protected $output = [];
    
    public function write($string){
        $this->output[] = $string;
    }
    
    public function getBody(){
        $body = new \System\Core\Str();

        foreach ($this->output as $output){
            $body = $body->append($output);
        }
        return (string)$body;
    }
}
