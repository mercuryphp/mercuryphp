<?php

namespace System\Core;

class Str implements \IteratorAggregate, \Countable {
    
    private $string = '';
    
    const FIRST_FIRST = 1;
    const FIRST_LAST = 2;
    const LAST_LAST = 3;
    
    /**
     * Initializes an instance of Str. If $string is specified, the instance is
     * initialized with a default $string.
     */
    public function __construct(string $string = ''){
        $this->string = $string;
    }
    
    /**
     * Gets the character at the $index position.
     * Throws System.Core.IndexOutOfRangeException when index is out of range.
     */
    public function charAt($index, $throwException = true) : Str {
        if(isset($this->string[$index])){
            return new Str($this->string[$index]);
        }
        if($throwException){
            throw new \System\Core\IndexOutOfRangeException(sprintf("The index '%s' was out or range.", $index));
        }
    }
    
    /**
     * Gets a new instance of Str with the character at $index position removed.
     * Throws System.Core.IndexOutOfRangeException when index is out of range.
     */
    public function removeAt($index, $throwException = true) : Str {
        if(isset($this->string[$index])){
            return new Str(substr_replace($this->string, '', $index, 1));
        }
        if($throwException){
            throw new \System\Core\IndexOutOfRangeException(sprintf("The index '%s' was out or range.", $index));
        }
    }
    
    /**
     * Gets a new instance of Str with $string inserted at $index position.
     * Throws System.Core.IndexOutOfRangeException when index is out of range.
     */
    public function insertAt($index, $string, $throwException = true) : Str {
        if(isset($this->string[$index])){
            return new Str(substr_replace($this->string, $string, $index, 0));
        }
        if($throwException){
            throw new \System\Core\IndexOutOfRangeException(sprintf("The index '%s' was out or range.", $index));
        }
    }
    
    /**
     * Gets the zero-based index of the first occurrence of the specified $char
     * in the current instance.
     */
    public function indexOf($string) : int {
        return stripos($this->string, $string);
    }
    
    /**
     * Gets the zero-based index of the last occurrence of the specified $string
     * in the current instance.
     */
    public function lastIndexOf(string $string) : int {
        return strripos($this->string, $string);
    }
    
    /**
     * Gets the sub string before the first occurrence of the specified $string
     * in the current instance.
     */
    public function getIndexOf(string $string) : Str {
        return $this->subString(0, $this->indexOf($string));
    }
    
    /**
     * Gets the sub string before the last occurrence of the specified $string
     * in the current instance.
     */
    public function getLastIndexOf(string $string) : Str {
        return $this->subString(0, $this->lastIndexOf($string));
    }
    
    /**
     * Gets the number of characters in the current instance.
     */
    public function count() : int {
        return strlen($this->string);
    }
    
    /**
     * Gets a new Str instance where the string is left padded with $char,
     * for a specified total $length.
     */
    public function paddLeft(int $length, string $char = ' ') : Str {
        return new Str(str_pad($this->string, $length, $char, STR_PAD_LEFT));
    }
    
    /**
     * Gets a new Str instance where the string is right padded with $char,
     * for a specified total $length.
     */
    public function paddRight(int $length, string $char = ' ') : Str {
        return new Str(str_pad($this->string, $length, $char, STR_PAD_RIGHT));
    }
    
    /**
     * Gets a new Str instance where all occurrences of $search is replaced 
     * with a $replace string. The $search argument can be either a string or
     * an array containing search strings.
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
     * the beginning and end of this instance.
     */
    public function trim($charList = null) : Str {
        return new Str(trim($this->string, $charList));
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the start of this instance.
     */
    public function leftTrim($charList = null) : Str {
        return new Str(ltrim($this->string, $charList));
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the end of this instance.
     */
    public function rightTrim($charList = null) : Str {
        return new Str(rtrim($this->string, $charList));
    }
    
    /**
     * Gets a new Str instance where the string is converted to uppercase.
     */
    public function toUpper() : Str {
        return new Str(strtoupper($this->string));
    }
    
    /**
     * Gets a new Str instance where the string is converted to lowercase.
     */
    public function toLower() : Str {
        return new Str(strtolower($this->string));
    }
    
    /**
     * Gets a new Str instance where the first character in the string is 
     * converted to uppercase.
     */
    public function toFirstUpper() : Str {
        return new Str(ucfirst($this->string));
    }
    
    /**
     * Gets a new Str instance where the first character in the string is 
     * converted to lowercase.
     */
    public function toFirstLower() : Str {
        return new Str(lcfirst($this->string));
    }
    
    /**
     * Gets a new Str instance with the specified string prepended to this 
     * instance.
     */
    public function prepend(string $string) : Str {
        return new Str($string.$this->string);
    }
    
    /**
     * Gets a new Str instance with the specified string appended to this 
     * instance.
     */
    public function append(string $string) : Str {
        return new Str($this->string.$string);
    }
    
    /**
     * Gets a new Str instance which is a sub string of this instance. If $start
     * is a string, the characters index position is used as the start position.
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
     * System.Collections.ArrayList containing the substrings. This method uses 
     * the preg_split() function. Special characters need to be escaped.
     */
    public function split(string $delimiter, int $limit = null, int $flags = PREG_SPLIT_NO_EMPTY) : \System\Collections\ArrayList {
        $array = preg_split('/'.$delimiter.'/', $this->string, $limit, $flags);
        return new \System\Collections\ArrayList($array);
    }
    
    /**
     * Gets a new Str instance where the string returned is between $fromChar 
     * and $toChar. The $mode parameter can be used to specifiy the start position 
     * of $fromChar and $toChar. There are three mode constants 
     * (FIRST_FIRST, FIRST_LAST and LAST_LAST). Gets an empty Str instance if
     * no match is found.
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
            case self::LAST_LAST:
                $pos1 = $this->lastIndexOf($fromChar);
                $pos2 = $this->lastIndexOf($toChar);
                break;
        }

        return new Str($this->subString((int)$pos1+1, (int)$pos2-$this->count()));
    }
    
    /**
     * Compares two strings by calculating the sum of all character ordinals in 
     * $string1 with the sum of all character ordinals in $string2. Gets an 
     * integer value that represents the difference. A negative return value means
     * $string1 is less than $string2. A zero value means both $string1 and $string2
     * are equal. A positive value means $string1 is more than $string2.
     */
    public function compareOrdinal(string $string1, string $string2) : int {
        $strings = [$string1, $string2];
        $counts = [];
        
        foreach($strings as $string){
            $len = strlen($string);
            $ord = 0;
            for($idx=0; $idx < $len; $idx++){
                $ord += ord($string[$idx]);
            }
            $counts[] = $ord;
        }
        return $counts[0] - $counts[1];
    }
    
    /**
     * Gets a boolean value indicating if the Str instance matches the specified 
     * regex $pattern.
     */
    public function match(string $pattern) : bool {
        if(preg_match($pattern, $this->string)){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a boolean value indicating if the Str instance contains the specified 
     * $string.
     */
    public function contains(string $string, bool $ignoreCase = true) : bool {
        $case = $ignoreCase ? 'i' : '';
        if(preg_match('/'.$string.'/'.$case, $this->string)){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a boolean value indicating if the end of this Str instance 
     * matches the specified $string.
     */
    public function endsWith(string $string, bool $ignoreCase = true) : bool {
        $case = $ignoreCase ? 'i' : '';
        if(preg_match('/'.$string.'$/'.$case, $this->string)){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a boolean value indicating if the beginning of this Str instance 
     * matches the specified $string.
     */
    public function startsWith(string $string, bool $ignoreCase = true) : bool {
        $case = $ignoreCase ? 'i' : '';
        if(preg_match('/^'.$string.'/'.$case, $this->string)){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a boolean value indicating if this Str instance equals $string.
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
     * the token to replace. The $transformations parameter can be used to
     * apply string manipulation to the values in $params. There are 5
     * transformation keywords (lc: lowercase, uc: uppercase, 
     * ucf: uppercase first, lcf: lowercase first, t: trim). Transformations can
     * be chained using "." e.g "lc.ucf" transforms MERCURY to Mercury.  
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
    
    /**
     * Gets an ArrayIterator object so that characters in this Str instance can
     * be iterated.
     */
    public function getIterator(){
        return new \ArrayIterator(array_map(function($char){
            return new Str($char);
        },
        str_split($this->string, 1)));
    }

    /**
     * Gets the Str instance as a string data type.
     */
    public function toString() : string {
        return (string)$this->string;
    }
    
    /**
     * Magic method. Alias of toString().
     */
    public function __toString() : string {
        return $this->toString();
    }
    
    /**
     * Sets the string and gets a new instance of Str.
     */
    public static function set($string){
        return new Str($string);
    }
    
    /**
     * Joins all elements in the $array using the specified $glue and returns a 
     * new instance of System.Core.Str
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
