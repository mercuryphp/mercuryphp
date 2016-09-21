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
    
    public function read(){
        ++$this->index;
        
        if($this->tokens->hasKey($this->index)){
            return true;
        }
        return false;
    }
    
    public function current(){
        return $this->tokens->get($this->index);
    }
    
    public function prev(){
        return $this->tokens->get($this->index-1);
    }
    
    public function next(){
        return $this->tokens->get($this->index+1);
    }
}

