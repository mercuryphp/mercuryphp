<?php

namespace System\Core;
 
final class Attribute {
    
    public static function getPropertyAttributes($object, $propertyName = null){
        
        if(!is_object($object)){
            throw new \RuntimeException(sprintf('Attribute::getPropertyAttributes() expects parameter 1 to be object, %s given', gettype($object)));
        }
        
        $refClass = new \ReflectionObject($object);
        $tokens = token_get_all(file_get_contents($refClass->getFileName()));

        $tmp = [];
        $attributes = [];
        foreach($tokens as $idx=>$token){

            if(isset($token[0])){
                switch($token[0]){
                    case T_COMMENT:
                        $attributeName = Str::set($token[1])->get('@', '(')->trim();
                        if((string)$attributeName){
                            try{
                                $tmp[] = Obj::getInstance($attributeName);
                            }
                            catch(\ReflectionException $re){
                                throw new AttributeException($re->getMessage());
                            }
                        }
                        break;
                        
                    case T_VARIABLE:
                        $attributes[substr($token[1], 1)] = $tmp;
                        $tmp = [];
                        break;
                }
            }
        }
        
        unset($tmp);
        
        if(null !== $propertyName){
            return array_key_exists($propertyName, $attributes) ? $attributes[$propertyName] : false;
        }
        return $attributes;
    }
    
    public static function getAttributes($object, $methodName){
        
        if(!is_object($object)){
            throw new \RuntimeException(sprintf('Attribute::getAttributes() expects parameter 1 to be object, %s given', gettype($object)));
        }
        
        $refClass = new \ReflectionObject($object);
        $tokens = token_get_all(file_get_contents($refClass->getFileName()));

        $comments = array();
        foreach($tokens as $idx=>$token){

            if(isset($token[1]) && $token[0] == T_COMMENT){
                $comments[$token[2]] = $token[1];
            }
            
            if(isset($token[1]) && ($token[1] == $methodName) && ($tokens[$idx -2][0] == T_FUNCTION)){
                $keys = array_reverse(array_keys($comments));
                foreach ($keys as $i => $key) { 
                    if($token[2] - ($i+$key+1) == 0){
                        $attribute = Str::set($comments[$key])->get('@', '(');

                        if((string)$attribute){
                            $args = (string)Str::set($comments[$key])->get('(', ')');
                            $args = $args ? str_getcsv($args, ',', '"') : array();
                            $comments[$key] = Obj::getInstance((string)$attribute->append('Attribute'), array_map('trim', $args));
                        }else{
                            unset($comments[$key]);
                        }
                    }else{
                        unset($comments[$key]);
                    }
                }
            }
        }
        return array_values($comments);
    }
}