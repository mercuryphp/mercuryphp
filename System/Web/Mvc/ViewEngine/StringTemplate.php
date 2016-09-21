<?php

namespace System\Web\Mvc\ViewEngine;

use System\Core\Str;
use System\Collections\ArrayList;

class StringTemplate {
    
    protected $tokens;
    
    public function __construct($string = null){
        
        $lines = explode("\n", $string);
        $this->tokens = new TemplateTokenCollection();
        $token = new Str();
        $openTag = false;    
        
        foreach($lines as $i => $line){
            $line = Str::set($line);
            
            foreach($line as $idx => $char){

                if ($char->equals('@') && $line->charAt($idx+1, false)->equals('{')){
                    if($openTag){
                        throw new \Exception('unclosed tag on line ' . $i);
                    }
                    
                    $this->tokens->add(new TemplateToken(1, (string)$token, $i+1));
                    $token = new Str();
                    $openTag = true;
                    continue;
                }
                
                if($char->equals('}') && $openTag){
                    $this->tokens->add(new TemplateToken(2, trim($token, '{'), $i+1));
                    
                    $token = new Str();
                    $openTag = false;
                    continue;
                }
                
                $token = $token->append($char);
            }
        }
        
        if($openTag){
            throw new \Exception('unclosed tag on line ' . $i);
        }
        $this->tokens->add(new TemplateToken(1, (string)$token, $i+1));
        
    }
    
    public function render(array $params = []) : string {
        $params = new \System\Collections\Dictionary($params);
        $output = new \System\Core\Str();
        
        while($this->tokens->read()){

            $currentToken = $this->tokens->current();
            
            switch($currentToken->getType()){
                case TemplateToken::T_STRING:
                    $output = $output->append($currentToken->getValue());
                    break;
                
                case TemplateToken::T_CODE:
                    $codeSegments = $this->processCode($currentToken->getValue());
                    $output = $output->append($this->processVariable($params, $codeSegments)); 
                    break;
            }
        }
        return $output;
    }
    
    protected function processCode($code){
        return new \System\Collections\ArrayList(array_map('trim', preg_split('/[.:]/', $code, null, PREG_SPLIT_NO_EMPTY)));
    }
    
    protected function processVariable($params, $codeSegments){ 
        if($codeSegments->count()){
            $variableName = $codeSegments->get(0);

            if($params->hasKey($variableName)){
                
                $value = $params->get($variableName);

                if(is_scalar($value)){
                    $codeSegments->removeAt(0, true);
                    
                    foreach($codeSegments as $funcSegment){
                        switch($funcSegment){
                            case 'toUpper()':
                                $value = strtoupper($value);
                                break;
                            default:
                                throw new \Exception('Unknown function  ' . $funcSegment);
                
                        }
                    }
                    return $value; 
                }else{
                    $codeSegments->removeAt(0, true);
                    return $this->processVariable(new \System\Collections\Dictionary($value), $codeSegments);
                }
            }else{
                throw new \Exception('Undefined variable ' . $variableName);
            }
        }
    }
}

