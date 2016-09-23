<?php

namespace System\Web\Mvc\ViewEngine;

class TemplateTokenCollection {
    
    protected $tokens;
    protected $index = -1;
    
    public function __construct(){
        $this->tokens = new \System\Collections\ArrayList();
    }
    
    public function add(TemplateToken $token){
        $this->tokens->add($token);
    }
    
    public function read() : bool {
        ++$this->index;
        
        if($this->tokens->hasKey($this->index)){
            return true;
        }
        return false;
    }
    
    public function current() : TemplateToken {
        return $this->tokens->get($this->index);
    }
    
    public function prev() : TemplateToken {
        return $this->tokens->get($this->index-1);
    }
    
    public function next() : TemplateToken {
        return $this->tokens->get($this->index+1);
    }
}

