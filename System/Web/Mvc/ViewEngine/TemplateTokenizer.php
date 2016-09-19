<?php

namespace System\Web\Mvc\ViewEngine;

use System\Core\Str;
use System\Collections\ArrayList;

class TemplateTokenizer {
    
    public function __construct($file){
        $lines = file($file);
        
        $token = new Str();
        $tokens = new TemplateTokenCollection();
        $openTag = false;    
        
        foreach($lines as $i => $line){
            $line = Str::set($line);
            
            foreach($line as $char){
                
                if ($char->equals('{')){
                    $tokens->add(new TemplateToken(1, $token, $i));
                    
                    $token = new Str();
                    $openTag = true;
                    continue;
                }
                
                if($char->equals('}') && $openTag){
                    $tokens->add(new TemplateToken(2, $token, $i));
                    
                    $token = new Str();
                    $openTag = false;
                    continue;
                }
                
                $token = $token->append($char);
            }
        }
        $tokens->add(new TemplateToken(1, $token, $i));
        
        print_R($tokens); exit;
    }
}

