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
     * Gets a new Str instance where all occurrences of a specified string in this 
     * instance is replaced with another specified string.
     * 
     * @param   mixed $search
     * @param   string $replace
     * @param   int $limit
     * @return  System.Core.Str
     */
    public function replace($search, string $replace, int $limit = -1) : Str {
        if(is_string($search)){
            $search[] = $search;
        }
        foreach($search as $item){
            $this->string = preg_replace('#'.$item.'#', $replace, $this->string, $limit);
        }
        return new Str($this->string);
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
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the start of this instance.
     * 
     * @param   string $charList = null
     * @return  System.Core.Str
     */
    public function leftTrim($charList = null) : Str{
        return new Str(ltrim($this->string, $charList));
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the end of this instance.
     * 
     * @param   string $charList = null
     * @return  System.Core.Str
     */
    public function rightTrim($charList = null) : Str {
        return new Str(rtrim($this->string, $charList));
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
     * Gets a boolean value indicating if this Str instance equals $string.
     * 
     * @param   array $string
     * @return  bool
     */
    public function equals(string $string) : bool {
        if($string == $this->string){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a new Str instance where template tokens are replaced with the
     * values from $params. $params must be a key/value array, where the key is
     * the token to replace.
     * 
     * @param   mixed $params
     * @return  System.Core.Str
     */
    public function template($params, array $transformations = []) : Str{
        $tokens = $this->tokenize('{', '}', false)[0];

        foreach($tokens as $idx => $token){
            list($type, $tokenName) = array_values($token);

            if($type=='T_TOKEN'){
                if(array_key_exists($tokenName, $params) && array_key_exists($tokenName, $transformations)){
                    $value = $params[$tokenName];
                    $transformation = strtolower($transformations[$tokenName]);
                    $args = explode('.',$transformation);
                    
                    foreach($args as $arg){
                        switch($arg){
                            case 'lc':
                                $value = strtolower($value);
                                break;
                            case 'uc':
                                $value = strtoupper($value);
                                break;
                            case 'ucf':
                                $value = ucfirst($value);
                                break;
                            case 'lcf':
                                $value = lcfirst($value);
                                break;
                            case 't':
                                $value = trim($value);
                                break;
                        }
                    }
                    $params[$tokenName] = $value;
                }
            }
            
            if(array_key_exists($tokenName, $params)){
                $this->string = str_replace('{'.$tokenName.'}', $params[$tokenName], $this->string);
            }else{
                $this->string = str_replace('{'.$tokenName.'}', '', $this->string);
            }
        }
        return new Str($this->string);
    }
    
    /**
     * Tokenizes the current instance and returns an instance of 
     * System.Collections.ArrayList that contains all the tokens.
     * 
     * @param   string $openingTag
     * @param   string $closingTag
     * @return  System.Collections.ArrayList
     */
    public function tokenize(string $openingTag, string $closingTag, $includeTags = true) : \System\Collections\ArrayList {
        $len = strlen($this->string);
        $token = '';
        $tokens = [];
        
        for($i=0; $i < $len; $i++){
            $char = $this->string[$i];

            if($char==$openingTag){
                if($token){
                    $tokens[0][] = ['type' => 'T_TEXT', 'value' => $token];
                    $tokens[1][] = $token;
                    $token = '';
                }
                continue;
            }
            if($char==$closingTag){
                $tokenValue = $openingTag.$token.$closingTag;
                if(!$includeTags){
                    $tokenValue = $token;
                }
                $tokens[0][] = ['type' => 'T_TOKEN', 'value' => $tokenValue];
                $tokens[1][] = $tokenValue;
                $token = '';
                continue;
            }

            $token.= $char;
        }
        $tokens[0][] = ['type' => 'T_TEXT', 'value' => $token];
        $tokens[1][] = $token;

        return new \System\Collections\ArrayList($tokens);
    }
    
    public function toString() : string {
        return (string)$this->string;
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
    
    /**
     * Joins all elements in the $array using the specified $glue and returns a 
     * new instance of System.Core.Str
     * 
     * @param   string $glue
     * @param   mixed $array
     * @param   bool $removeEmptyEntries = true
     * @return  System.Std.Str
     */
    public static function join($glue, $array, $removeEmptyEntries = true) : Str {
        if($array instanceof \System\Collections\Collection){
            $array = $array->toArray();
        }
        $join = '';
        if(is_array($array)){
            foreach($array as $value){
                if(is_scalar($value)){
                    $value = trim($value);
                    if($removeEmptyEntries){
                        if($value !=''){
                            $join.= $value.$glue;
                        }
                    }else{
                        $join.= $value.$glue;
                    }
                }
            }
        }
        return new Str(trim($join, $glue));
    }
}
