<?php

namespace System\Mvc\Http;

class HttpCookieCollection implements \IteratorAggregate {

    protected $collection = [];
    
    /**
     * Initializes an instance of HttpCookieCollection. If $cookies is specified,
     * the instance is initialized with a default Cookie collection.
     */
    public function __construct(array $cookies = []){
        foreach($cookies as $name => $value){
            $httpCookie = new HttpCookie($name, $value);
            $this->add($httpCookie);
        }
    }
    
    /**
     * Adds a HttpCookie instance to the Cookie collection.
     */
    public function add(HttpCookie $httpCookie){
        $this->collection[$httpCookie->getName()] = $httpCookie;
    }
    
    public function get($name){
        if($this->hasCookie($name)){
            return $this->collection[$name];
        }
    }

    public function hasCookie($name) : bool{
        if(array_key_exists($name, $this->collection)){
            return true;
        }
        return false;
    }

    public function toArray() : array{
        return $this->collection;
    }

    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
}
