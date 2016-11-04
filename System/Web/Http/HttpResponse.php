<?php

namespace System\Web\Http;

class HttpResponse {
    
    protected $contentType;
    protected $encoding;
    protected $contentLength = 0;
    protected $cookies;
    protected $output;
    protected $redirect;
    
    public function __construct() {
        $this->cookies = new HttpCookieCollection([]);
        $this->output = new HttpResponseOutput();
    }
    
    public function setContentType(string $contentType){
        $this->contentType = $contentType;
        return $this;
    }
    
    public function getContentType() : string {
        return $this->contentType;
    }
    
    public function setContentEncoding(string $encoding){
        $this->encoding = $encoding;
        return $this;
    }
    
    public function getContentEncoding() : string {
        return $this->encoding;
    }
    
    public function setContentLength(int $length){
        $this->contentLength = $length;
        return $this;
    }
    
    public function getContentLength() : int {
        return $this->contentLength;
    }

    public function getOutput() : HttpResponseOutput {
        return $this->output;
    }
    
    /**
     * Gets a value from the cookie collection. If $name is not specified then 
     * gets a System.Web.Http.HttpCookieCollection object of all cookies. 
     */
    public function getCookies($name = null) {
        if(null === $name){
            return $this->cookies;
        }
        if($this->cookies->hasKey($name)){
            return $this->cookies->get($name);
        }else{
            $cookie = new HttpCookie($name);
            $this->cookies->add($cookie);
            return $cookie;
        }
    }
    
    public function redirect(string $location){
        $this->redirect = $location;
    }

    public function flush(){
        foreach($this->cookies as $cookie){
            setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpires(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
        }
        if($this->redirect){
            header('Location: ' . $this->redirect);
            exit;
        }
        echo $this->output->getBody();
    }
}
