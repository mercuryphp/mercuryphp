<?php

namespace System\Web\Http;

final class HttpCookie {

    private $name;
    private $value;
    private $expires;
    private $path;
    private $domain;
    private $isSecure;
    private $isHttpOnly;
    
    /**
     * Initializes a new instance of HttpCookie.
     */
    public function __construct(string $name, string $value = '', $expires = 0, $path = '/', $domain = '', $isSecure = false, $isHttpOnly = true){
        $this->name = $name;
        $this->value = $value;
        $this->expires = $expires;
        $this->path = $path;
        $this->domain = $domain;
        $this->isSecure = $isSecure;
        $this->isHttpOnly = $isHttpOnly;
    }

    /**
     * Sets the cookie name.
     */
    public function setName(string $name) : HttpCookie {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Gets the cookie name.
     */
    public function getName() : string {
        return $this->name;
    }
    
    /**
     * Sets the cookie value.
     */
    public function setValue(string $value) : HttpCookie {
        $this->value = $value;
        return $this;
    }
    
    /**
     * Gets the cookie value.
     */
    public function getValue() : string {
        return $this->value;
    }

    /**
     * Sets the expiration date and time for the cookie.
     */
    public function setExpires($expires) : HttpCookie {
        $this->expires = $expires;
        return $this;
    }
    
    /**
     * Gets the expiration date and time for the cookie.
     */
    public function getExpires(){
        return $this->expires;
    }
    
    /**
     * Sets the path on the server where the cookie will be available.
     */
    public function setPath(string $path) : HttpCookie {
        $this->path = $path;
        return $this;
    }
    
    /**
     * Gets the path on the server where the cookie will be available.
     */
    public function getPath() : string {
        return $this->path;
    }
    
    /**
     * Sets the domain to associate the cookie with.
     */
    public function setDomain(string $domain) : HttpCookie {
        $this->domain = $domain;
        return $this;
    }
    
    /**
     * Gets the domain to associate the cookie with.
     */
    public function getDomain() : string {
        return $this->domain;
    }
    
    /**
     * Sets or gets a value indicating that the cookie should only be transmitted 
     * over a secure HTTPS connection from the client.
     */
    public function isSecure(bool $bool = null){
        if(null === $bool){
            return $this->isSecure;
        }
        $this->isSecure = $bool;
    }

    /**
     * Sets or gets a value indicating that the cookie should be accessible only 
     * through the HTTP protocol.
     */
    public function isHttpOnly(bool $bool = null){
        if(null === $bool){
            return $this->isHttpOnly;
        }
        $this->isHttpOnly = $bool;
    }
}