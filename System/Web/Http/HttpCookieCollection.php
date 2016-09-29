<?php

namespace System\Web\Http;

class HttpCookieCollection extends \System\Collections\Collection {

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
}
