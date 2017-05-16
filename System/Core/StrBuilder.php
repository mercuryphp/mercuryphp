<?php

namespace System\Core;

/**
 * A string manipulation class.
 */
class StrBuilder {
    
    protected $string = '';

    public function __construct($string = ''){
        $this->string = new Str($string);
    }

    /**
     * Gets a new Str instance with the specified string appended to this 
     * instance.
     */
    public function append(string $string) : StrBuilder{
        $this->string .= $string;
        return $this;
    }
    
    /**
     * Gets a new Str instance with a line break appended to this 
     * instance.
     */
    public function appendLine(int $multiplier = 1) : StrBuilder{
        $this->string .= str_repeat(PHP_EOL, $multiplier);
        return $this;
    }

    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the beginning and end of this instance.
     */
    public function trim($charList = " \n") : StrBuilder{
        $this->string = trim($this->string, $charList);
        return $this;
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the start of this instance.
     */
    public function leftTrim($charList = " \n") : StrBuilder{
        $this->string = ltrim($this->string, $charList);
        return $this;
    }
    
    /**
     * Gets a new Str instance where all occurrences of $char are removed from 
     * the end of this instance.
     */
    public function rightTrim($charList = " \n") : StrBuilder{
        $this->string = rtrim($this->string, $charList);
        return $this;
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

