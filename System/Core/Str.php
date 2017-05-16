<?php

namespace System\Core;

/**
 * A string manipulation class.
 */
class Str {
    
    protected $string = '';
    
    const FIRST_FIRST = 1;
    const FIRST_LAST = 2;
    const LAST_LAST = 3;
    
    public function __construct($string = ''){
        $this->string = $string;
    }
    
    /**
     * Sets the string and gets a new instance of Str.
     */
    public static function set(string $string) : Str{
        return new Str($string);
    }
    
    /**
     * Gets the zero-based index of the first occurrence of the specified $string
     * in the current instance.
     */
    public function indexOf(string $string){
        return stripos($this->string, $string);
    }
    
    /**
     * Gets the zero-based index of the last occurrence of the specified $string
     * in the current instance.
     */
    public function lastIndexOf(string $string){
        return strripos($this->string, $string);
    }
    
    /**
     * Gets the string before the first occurrence of the specified $string
     * in the current instance.
     */
    public function getIndexOf(string $string){
        return $this->subString(0, $this->indexOf($string));
    }
    
    /**
     * Gets the string before the last occurrence of the specified $string
     * in the current instance.
     */
    public function getLastIndexOf(string $string){
        return $this->subString(0, $this->lastIndexOf($string));
    }
    
    /**
     * Gets the string between $fromChar and $toChar. The $mode parameter can 
     * be used to specify the start position of $fromChar and $toChar. 
     * There are three mode constants (FIRST_FIRST, FIRST_LAST and LAST_LAST). 
     * Gets an empty string if no match is found.
     */
    public function get(string $fromChar, string $toChar, $mode = Str::FIRST_FIRST) : Str{
        switch ($mode){
            case self::FIRST_FIRST:
                $pos1 = $this->indexOf($fromChar);
                $pos2 = $this->indexOf($toChar);
                break;
            case self::FIRST_LAST:
                $pos1 = $this->indexOf($fromChar);
                $pos2 = $this->lastIndexOf($toChar);
                break;
            case self::LAST_LAST:
                $pos1 = $this->lastIndexOf($fromChar);
                $pos2 = $this->lastIndexOf($toChar);
                break;
        }
        if(false === $pos1 || false === $pos2 ){
            return new Str();
        }

        return new Str($this->subString((int)$pos1+1, (int)$pos2-$this->count()));
    }
    
    /**
     * Gets a substring from this instance. If $start
     * is a character, the characters index position is used as the start position.
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
     * Placeholders in this instance are replaced by tokens enclosed in curly braces.
     * The method is supplied an array where the array keys are used to replace 
     * placeholders in the string with the array value.
     */
    public function tokens(array $tokens){
        $string = $this->string;
        foreach($tokens as $token=>$value){
            $string = str_replace('{' . $token . '}' , $value, $string);
        }
        return new Str($string);
    }
    
    /**
     * Gets a copy of this string in lowercase.
     */
    public function toLower() : Str{
        return new Str(strtolower($this->string));
    }
    
    /**
     * Gets a copy of this string in uppercase.
     */
    public function toUpper() : Str{
        return new Str(strtoupper($this->string));
    }
    
    /**
     * Gets a copy of this string where the first character is converted to uppercase.
     */
    public function toUpperFirst() : Str{
        return new Str(ucfirst($this->string));
    }
    
    /**
     * Gets a new Str instance where all occurrences of $search is replaced 
     * with a $replace string. The $search argument can be either a string or
     * an array containing search strings.
     */
    public function replace($search, string $replace, $count = null) : Str {
        if(is_string($search)){
            $search = [$search];
        }
        return new Str(str_replace($search, $replace, $this->string, $count));
    }
    
    /**
     * Gets a new Str instance with the specified string appended to this 
     * instance.
     */
    public function append(string $string) : Str {
        return new Str($this->string . $string);
    }
    
    /**
     * Gets a new Str instance with a line break appended to this 
     * instance.
     */
    public function appendLine(int $multiplier = 1) : Str {
        return new Str($this->string . $this->string.str_repeat(PHP_EOL, $multiplier));
    }
    
    public function first(){
        return new Str(substr($this->string, 0, 1));
    }
    
    public function last(){
        return new Str($this->string = substr($this->string, -1, 1));
    }
    
    /**
     * Splits a string into substrings using the $delimiter and returns a 
     * System.Core.Arr containing the substrings. This method uses 
     * the preg_split() function. Special characters need to be escaped.
     */
    public function split(string $delimiter, int $limit = null, int $flags = PREG_SPLIT_NO_EMPTY) : \System\Core\Arr {
        $array = preg_split('/'.$delimiter.'/', $this->string, $limit, $flags);
        return new \System\Core\Arr($array);
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the beginning and end of this instance.
     */
    public function trim($charList = " \n") : Str {
        return new Str(trim($this->string, $charList));
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the start of this instance.
     */
    public function leftTrim($charList = " \n") : Str {
        return new Str(ltrim($this->string, $charList));
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the end of this instance.
     */
    public function rightTrim($charList = " \n") : Str {
        return new Str(rtrim($this->string, $charList));
    }
    
    /**
     * Gets a boolean value indicating if this Str instance equals $string.
     */
    public function equals($string) : bool{
        if($this->string == $string){
            return true;
        }
        return false;
    }
    
    /**
     * Gets the number of characters in the current instance.
     */
    public function count() : int{
        return strlen($this->string);
    }
    
    public function toString() : string{
        return (string)$this->string;
    }

    public function __toString(){
        return $this->toString();
    }
}

