<?php

namespace System\Core;

class Str {
    
    private $string = '';
    
    public function __construct($string){
        $this->string = $string;
    }

    public static function set($string){
        return new Str($string);
    }
    
    public function subString($start, $length = null){
        if(is_string($start)){
            $start = stripos($this->string, $start);
        }
        if($length){
            return new Str(substr($this->string, $start, $length));
        }
        return new Str(substr($this->string, $start));
    }


    public function toString(){
        return $this->string;
    }
    
    public function __toString(){
        return $this->toString();
    }
}
