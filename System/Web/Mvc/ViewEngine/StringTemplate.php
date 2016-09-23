<?php

namespace System\Web\Mvc\ViewEngine;

use System\Core\Str;
use System\Collections\ArrayList;

class StringTemplate {
    
    protected $lines;
    protected $tokens;
    protected $keywords;
    
    public function __construct($string = null){
        $this->keywords = new \System\Collections\ArrayList([
            'foreach',
            'condition'
        ]);
        
        $this->lines = explode("\n", $string);
        $this->tokens = new TemplateTokenCollection();
        $token = new Str();
        $openTag = false;    
        
        foreach($this->lines as $lineNumber => $line){
            $line = Str::set($line);
            
            foreach($line as $idx => $char){

                if ($char->equals('@') && $line->charAt($idx+1, false)->equals('{')){
                    if($openTag){
                        throw new \Exception('unclosed tag on line ');
                    }
                    
                    $this->tokens->add(new TemplateToken(1, (string)$token), $lineNumber);
                    $token = new Str();
                    $openTag = true;
                    continue;
                }

                if($char->equals('}') && $openTag){
                    $this->tokens->add(new TemplateToken(2, trim($token, '{')), $lineNumber);
                    
                    $token = new Str();
                    $openTag = false;
                    continue;
                }
                
                $token = $token->append($char);
            }
        }

        if($openTag){
            throw new \Exception('unclosed tag on line');
        }
        $this->tokens->add(new TemplateToken(1, (string)$token, $lineNumber));
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
                    $currentToken->getValue();
                    $firstSegment = $currentToken->getValue()->split(' ')->first();
                    
                    if($this->keywords->contains($firstSegment)){
                        $keyword = 'compile'. $firstSegment;
                        $output = $output->append($this->$keyword($currentToken->getValue(), $params));
                    }else{
                        $codeSegments = $currentToken->getValue()->split('\.');
                        $output = $output->append($this->compileVariable($codeSegments, $currentToken->getLineNumber(), $params)); 
                    }
                    break;
            }
        }
        
        return $output;
    }
    
    protected function compileVariable($codeSegments, $lineNumber, $params){

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
                                throw new StringTemplateParseException("Unknown string function " . $funcSegment . " near '...%s'", $this->getErrorLine($lineNumber));
                        }
                    }
                    return $value; 
                }else{
                    $codeSegments->removeAt(0, true);
                    
                    if(!$codeSegments->count()){
                        throw new StringTemplateParseException("Cannot convert array to string near '...%s'", $this->getErrorLine($lineNumber));
                    }
                    return $this->compileVariable($codeSegments, $lineNumber, new \System\Collections\Dictionary($value));
                }
            }else{
                throw new \Exception('Undefined variable ' . $variableName);
            }
        }
    }
    
    protected function compileForeach($code, $params){
        
        $codeSegments = new \System\Collections\ArrayList(array_map('trim', preg_split('/[. ]/', $code, null, PREG_SPLIT_NO_EMPTY)));
        $segmentCode = new Str();
        $foreachCounter = 1;
        $output = new \System\Core\Str();

        while($this->tokens->read()){
            $currentToken = $this->tokens->current();

            if(Str::set($currentToken->getValue())->startsWith('foreach')){
                ++$foreachCounter;
            }
            if($currentToken->getValue() == '/foreach'){

                if($foreachCounter==1){ 
                    break;
                }
                --$foreachCounter;
            }

            if($currentToken->getType() == TemplateToken::T_STRING){
                $segmentCode = $segmentCode->append($currentToken->getValue());
            }else{
                $segmentCode = $segmentCode->append('@{'.$currentToken->getValue().'}');
            }
        }

        $collectionName = $codeSegments->get(1);
        $itemKey = $codeSegments->get(2);
        $itemName = $codeSegments->get(4);

        if($params->hasKey($collectionName)){
            $collection = $params->get($collectionName);

            foreach($collection as $key => $item){
                $template = new StringTemplate($segmentCode);
                $output = $output->append($template->render($params->merge([$itemName => $item, $itemKey => $key])->toArray()));
            }
        }
        return $output;
    }
    
    protected function compileCondition($code, $params){
        //print $code; exit;
        $code = Str::set($code);
        $tokens = new TemplateTokenCollection();
        $token = new Str();
        $openQuote = false;
        $splitChars = [
            '=' => TemplateToken::T_OPERATOR,
            '<' => TemplateToken::T_OPERATOR,
            '>' => TemplateToken::T_OPERATOR,
            '?' => TemplateToken::T_TEN,
            ':' => TemplateToken::T_NAK
        ];
            
        foreach($code as $idx => $char){
            
            if(in_array($char, [' ', '=', '<', '>', '?', ':', '"'])){
                
                if(array_key_exists((string)$char, $splitChars)){
                    $tokens->add(new TemplateToken($splitChars[(string)$char], $char, 1)); 
                }

                if($char == '"'){
                    $openQuote = $openQuote ? false : true;
                    continue;
                }

                if(!$openQuote){

                    $tokens->add(new TemplateToken(2, (string)$token->toString(), 1));
                    $token = new Str();

                    continue;
                }
            }

            $token = $token->append($char);
        }

        $conditionValue = null;
        $conditionTestValue = null;
        $operation = null;
print_R($tokens); exit;
        while($tokens->read()){
            $currentToken = $tokens->current();
            switch($currentToken->getType()){
                case TemplateToken::T_OPERATOR:
                    $prevToken = $tokens->prev(); 
                    $nextToken = $tokens->next(); 

                    $codeSegments = $prevToken->getValue()->split('\.');
                    $conditionValue = $this->compileVariable($codeSegments, $currentToken->getLineNumber(), $params); 

                    if($nextToken->getType() == TemplateToken::T_OPERATOR){
                        $operation = $currentToken->getValue().$nextToken->getValue();
                        $tokens->read();
                    }else{
                        $operation = $currentToken->getValue();
                    }
                    break;
                    
                case TemplateToken::T_TEN:
                    $conditionTestValue = $tokens->prev()->getValue(); 
                    break;
                
                case TemplateToken::T_NAK:
                    $xpr1 = $tokens->prev()->getValue(); 
                    $xpr2 = $tokens->next()->getValue(); 
                    break;
            }
        }
        
        print "$conditionValue $operation $conditionTestValue $xpr1 $xpr2";
        exit;
    }


    protected function getErrorLine($lineNumber){
        return htmlspecialchars(trim($this->lines[$lineNumber]));
    }
}

