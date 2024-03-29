<?php

namespace System\Core;

/**
 * An array helper class.
 */
class Arr implements \IteratorAggregate, \ArrayAccess, \Countable {
    
    protected $array = [];
    
    public function __construct(array $array = []){
        $this->array = $array;
    }
    
    public function select(string $fields){
        $fields = explode(',', $fields);

        $tmp = [];
        foreach($fields as $field){
            $tmp[$field] = $this->array[$field];
        }
        $this->array = $tmp;
        unset($tmp);
        return $this;
    }
    
    /**
     * Gets a boolean value that determines if the array contains the specified $key.
     */
    public function hasKey($key){
        if(array_key_exists($key, $this->array)){
            return true;
        }
        return false;
    }
    
    public function merge($data){
        $this->array = array_merge($this->array, $data);
    }
    
    /**
     * Adds a value into the array. This method is a variadic method. It can 
     * accept one or two arguments. If one argument is supplied, the argument 
     * will be used as the array value. If two arguments are supplied, the first
     * argument will be used as the array key and the second as the array value.
     * 
     * Example: add("value") or add("key", "value").
     * 
     * Throws InvalidArgumentException if an item with the same key already exists.
     */
    public function add(...$args) : Arr{
        if(count($args) == 2){
            $key = $args[0];
            $value = $args[1];
        }else{
            $key = null;
            $value = $args[0];
        }
        
        if(null !=$key){
            if($this->hasKey($key)){
                throw new \InvalidArgumentException(sprintf("An item with the key '%s' already exists in the array. Use the set(key, value) method to override the item value.", $key));
            }
            $this->array[$key] = $value;
        }else{
            $this->array[] = $value;
        }
        return $this;
    }
    
    public function first(){
        return reset($this->array);
    }

    /**
     * Adds or overrides an item in the array. This method does not check if an
     * item with the same key already exists.
     */
    public function set($key, $value) : Arr{
        $this->array[$key] = $value;
        return $this;
    }
    
    public function get($key, $default = null){
        if($this->hasKey($key)){
            return $this->array[$key];
        }
        if(null !== $default){
            return $default;
        }
        return false;
    }
    
    /**
     * Removes an element from the array using the specified $key. Numeric
     * keys are reindexed if $reindex is set to true. 
     */
    public function removeAt($key, bool $reindex = false) : bool {
        if($this->hasKey($key)){
            unset($this->array[$key]);
            
            if($reindex){
                $this->array = array_values($this->array);
            }
            return true;
        }
        return false;
    }
    
    public function join(string $glue = '') : Str{
        return new Str(join($glue, $this->array));
    }


    public function map($func){
        $this->array = array_map($func, $this->array);
        return $this;
    }

    /**
     * Gets the underlying PHP array for this instance.
     */
    public function toArray() : array{
        return $this->array;
    }
    
    public function getIterator() : \ArrayIterator{
        return new \ArrayIterator($this->array);
    }

    public function offsetExists($offset) : bool{
        if (array_key_exists($offset, $this->array)){
            return true;
        }
        return false;
    }

    public function offsetGet($offset){
        return $this->array[$offset];
    }
    
    public function offsetSet($offset, $value){
        $this->array[$offset] = $value;
    }

    public function offsetUnset($offset){
        unset($this->array[$offset]);
    }
    
    public function count() : int {
        return count($this->array);
    }
    
    public static function split(string $delimiter, string $string){
        return new Arr(explode($delimiter, $string));
    }
    
    public static function fromObject($object){
        return new Arr(Obj::getProperties($object));
    }
}