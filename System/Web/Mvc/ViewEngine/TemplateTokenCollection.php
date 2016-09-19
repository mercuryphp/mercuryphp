<?php

namespace System\Web\Mvc\ViewEngine;

class TemplateTokenCollection {
    
    protected $tokens = [];
    
    public function add(TemplateToken $token){
        $this->tokens[] = $token;
    }
}

