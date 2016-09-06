<?php

namespace System\Core;

class Str {
    
    private $string = '';
    
    const FIRST_FIRST = 1;
    const FIRST_LAST = 2;
    const LAST_FIRST = 3;
    const LAST_LAST = 4;
    
    public function __construct($string){
        $this->string = $string;
    }
    
    /**
     * Gets the zero-based index of the first occurrence of the specified $char
     * in the current instance.
     * 
     * @param   string $string
     * @return  int
     */
    public function indexOf($string) : int {
        return stripos($this->string, $string);
    }
    
    /**
     * Gets the zero-based index of the last occurrence of the specified $char
     * in the current instance.
     * 
     * @param   string $string
     * @return  int
     */
    public function lastIndexOf($string) : int {
        return strripos($this->string, $string);
    }
    
    /**
     * Gets the zero-based index of the last occurrence of the specified $char
     * in the current instance.
     * 
     * @param   string $string
     * @return  System.Core.Str
     */
    public function getLastIndexOf($string) : Str {
        return $this->subString(0, $this->lastIndexOf($string));
    }
    
    /**
     * Gets the number of characters in the current instance.
     * 
     * @return  int
     */
    public function length() : int {
        return strlen($this->string);
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * this instance.
     * 
     * @param   string $charList = null
     * @return  System.Core.Str
     */
    public function trim($charList = null) : Str {
        return new Str(trim($this->string, $charList));
    }
    
    /**
     * Gets a new Str instance which is a sub string of this instance.
     * 
     * @param   mixed $start
     * @param   int $length = null
     * @return  System.Core.Str
     */
    public function subString($start, $length = null) : Str {
        if(is_string($start)){
            $start = stripos($this->string, $start);
        }
        if($length){
            return new Str(substr($this->string, $start, $length));
        }
        return new Str(substr($this->string, $start));
    }
    
    /**
     * Splits a string into substrings using the $delimiter and returns a 
     * System.Collections.ArrayList containing the substrings.
     * 
     * @param   string $delimiter
     * @param   int $limit = null
     * @param   int $flags
     * @return  System.Collections.ArrayList
     */
    public function split(string $delimiter, int $limit = null, int $flags = PREG_SPLIT_NO_EMPTY) : \System\Collections\ArrayList {
        $array = preg_split('/'.$delimiter.'/', $this->string, $limit, $flags);
        return new \System\Collections\ArrayList($array);
    }
    
    /**
     * Gets a new Str instance which is a substring of this instance using a 
     * $fromChar and a $toChar.
     * 
     * @param   string $fromChar
     * @param   string $toChar
     * @param   string $mode
     * @return  System.Code.Str
     */
    public function get(string $fromChar, string $toChar, $mode = Str::FIRST_FIRST) : Str {
        switch ($mode){
            case self::FIRST_FIRST:
                $pos1 = $this->indexOf($fromChar);
                $pos2 = $this->indexOf($toChar);
                break;
            case self::FIRST_LAST:
                $pos1 = $this->indexOf($fromChar);
                $pos2 = $this->lastIndexOf($toChar);
                break;
            case self::LAST_FIRST:
                $pos1 = $this->lastIndexOf($fromChar);
                $pos2 = $this->indexOf($toChar);
                break;
            case self::LAST_LAST:
                $pos1 = $this->lastIndexOf($fromChar);
                $pos2 = $this->lastIndexOf($toChar);
                break;
        }

        return new Str($this->subString((int)$pos1+1, (int)$pos2-$this->length()));
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
    
    /**
     * Tokenizes the current instance and returns an instance of 
     * System.Collections.ArrayList that contains all the tokens.
     * 
     * @param   string $openingChar
     * @param   string $closingChar
     * @return  System.Collections.ArrayList
     */
    public function tokenize(string $openingChar, string $closingChar){
        $len = strlen($this->string);
        $token = '';
        $tokens = new \System\Collections\ArrayList();
        
        for($i=0; $i < $len; $i++){
            $char = $this->string[$i];

            if($char==$openingChar){
                if($token){
                    $tokens->add($token);
                    $token = '';
                }
                continue;
            }
            if($char==$closingChar){
                $tokens->add($openingChar.$token.$closingChar);
                $token = '';
                continue;
            }

            $token.= $char;
            
            if($i==strlen($this->string)-1){
                $tokens->add($token);
            }
        }
        return $tokens;
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
