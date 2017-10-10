<?php

namespace System\Mvc\View;

class StringView{
    
    protected $vars;
    protected $tokens = [];
    
    public function __construct(string $string){
        
        $this->vars = new \System\Core\Arr();
        
        $len = strlen($string);
        $codeBlock = false;
        $currentChar = '';
        $nextChar = '';
        $token = '';
        
        for($i = 0; $i < $len; $i++){
            $currentChar = $string[$i];
            $nextChar = isset($string[$i+1]) ? $string[$i+1] : '';
            
            if($currentChar == '{' && $nextChar == '{'){
                $codeBlock = true;
                $this->tokens[] = ['T_STRING', $token];
                $token = '';
                $i++;
                continue;
            }
            
            if($codeBlock && $currentChar == '}' && $nextChar == '}'){
                $codeBlock = false;
                $this->tokens[] = ['T_VAR', trim($token)];
                $token = '';
                $i++;
                continue;
            }
            
            $token.= $currentChar;
        }
        $this->tokens[] = ['T_STRING', $token];
    }
    
    public function addVar(string $name, $value){
        $this->vars->add($name, $value);
    }

    public function render(){

        $template = new \System\Core\StrBuilder();
        
        foreach($this->tokens as $token){
            
            switch ($token[0]) {
                case 'T_VAR':
                    $segments = explode('.', $token[1]);
                    if(count($segments) > 1){
                        $segments = array_reverse($segments);
                        $obj = $this->vars->get(array_pop($segments));
                        $value = $this->getObjectValue($obj, $segments);
                        $template->append($value);
                    }else{
                        if($this->vars->hasKey($token[1])){
                            $template->append($this->vars->get($token[1]));
                        }
                    }
                    break;
                default:
                    $template->append($token[1]);
                    break;
            }
        }
        return $template;
    }

    protected function getObjectValue($obj, $segments){                 
        if(is_object($obj)){
            $value = \System\Core\Obj::getProperty($obj, array_pop($segments));
            
            if(is_object($value)){
                return $this->getObjectValue($value, $segments);
            }
            return $value;
        }
    }
}