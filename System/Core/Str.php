<?php

namespace System\Core;

class Str {
    
    private $string = '';
    
    public function __construct($string){
        $this->string = $string;
    }
    
    /**
     * Gets a new Str instance which is a sub string of this instance.
     * 
     * @param   mixed $start
     * @param   int $length = null
     * @return  System.Core.Str
     */
    public function subString($start, $length = null){
        if(is_string($start)){
            $start = stripos($this->string, $start);
        }
        if($length){
            return new Str(substr($this->string, $start, $length));
        }
        return new Str(substr($this->string, $start));
    }
    
    /**
     * Gets a new Str instance where template tokens are replaced with the
     * values from $params. $params must be a key/value array, where the key is
     * the token to replace.
     * 
     * @param   array $params
     * @return  System.Core.Str
     */
    public function template($params){
        foreach($params as $key=>$val){
            $this->string = str_replace('{'.$key.'}', $val, $this->string);
        }
        return new Str($this->string);
    }
    
    public function toString(){
        return $this->string;
    }
    
    public function __toString(){
        return $this->toString();
    }
    
    /**
     * Sets the string and gets a new instance of Str.
     * 
     * @param   string $string
     * @return  System.Core.Str
     */
    public static function set($string){
        return new Str($string);
    }
}
