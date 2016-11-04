<?php

namespace System\Web\Http;

use System\Core\Str;
use System\Core\Obj;
use System\Collections\Dictionary;

final class HttpRequest {
    
    private $applicationPath;
    private $url;
    private $urlReferrer;
    private $server;
    private $routeData;
    private $query;
    private $post;
    private $params;
    private $cookies;
    private $inputStream;
    private $userLanguages;

    /**
     * Initializes an instance of HttpRequest.
     */
    public function __construct() {
        $this->server = new Dictionary($_SERVER);
        $this->routeData = new Dictionary(); 
        $this->url = new Uri($this->server->getString('REQUEST_SCHEME').'://'.$this->server->getString('HTTP_HOST').':'.$this->server->getString('SERVER_PORT').$this->server->getString('REQUEST_URI'));
        $this->urlReferrer = new Uri($this->server->getString('HTTP_REFERER'));
        $this->query = new Dictionary($_GET);
        $this->post = new Dictionary($_POST);
        $this->params = new Dictionary($_REQUEST);
        $this->cookies = new HttpCookieCollection($_COOKIE);
        $this->inputStream = file_get_contents("php://input"); 
        $this->userLanguages = Str::set($this->server->getString('HTTP_ACCEPT_LANGUAGE'))->split(',')->each(function($value){
            return explode(';', $value)[0];
        });
    }
    
    /**
     * Sets the physical file system path of the application's root directory.
     */
    public function setApplicationPath(string $applicationPath){
        $this->applicationPath = $applicationPath;
    }
    
    /**
     * Gets the physical file system path of the application's root directory.
     */
    public function getApplicationPath() : string {
        return $this->applicationPath;
    }
    
    /**
     * Gets the request URI without query variables.
     */
    public function getRouteData() : Dictionary {
        return $this->routeData;
    }
    
    /**
     * Gets the request URI without query variables.
     */
    public function getUrl() : Uri {
        return $this->url;
    }
    
    /**
     * Gets the request URI without query variables.
     */
    public function getUrlReferrer() : Uri {
        return $this->urlReferrer;
    }
    
    /**
     * Gets the user agent string of the client browser.
     */
    public function getUserAgent() : string {
        return $this->server->get('HTTP_USER_AGENT');
    }
    
    /**
     * Gets the IP of the client.
     */
    public function getClientAddress(){
        return $this->server->get('REMOTE_ADDR');
    }
    
    /**
     * Gets a list of client supported languages.
     */
    public function getUserLanguages() : \System\Collections\ArrayList {
        return $this->userLanguages;
    }
    
    /**
     * Gets a value from the query collection. If $name does not exist and 
     * $default is specified then gets $default. If $name is not specified then 
     * gets a System.Collections.Dictionary object of all query variables. 
     */
    public function getQuery($name = null, $default =  null) {
        if(null === $name){
            return $this->query;
        }
        return $this->query->get($name, $default);
    }
    
    /**
     * Gets a value from the post collection. If $name does not exist and 
     * $default is specified then gets $default. If $name is not specified then 
     * gets a System.Collections.Dictionary object of all post variables. 
     */
    public function getPost($name = null, $default =  null) {
        if(null === $name){
            return $this->post;
        }
        return $this->post->get($name, $default);
    }

    /**
     * Gets a value from the params collection, which is a combination of $_GET
     * and $_POST. If $name does not exist and $default is specified then gets
     * $default. If $name is not specified then gets a System.Collections.Dictionary
     * object of all params. 
     */
    public function getParams($name = null, $default =  null) {
        if(null === $name){
            return $this->params;
        }
        return $this->params->get($name, $default);
    }
    
    /**
     * Gets a value from the server collection. If $name does not exist and 
     * $default is specified then gets $default. If $name is not specified then 
     * gets a System.Collections.Dictionary object of all server variables. 
     */
    public function getServer($name = null, $default =  null) {
        if(null === $name){
            return $this->post;
        }
        return $this->post->get($name, $default);
    }
    
    /**
     * Gets a value from the cookie collection. If $name does not exist and 
     * $default is specified then gets $default. If $name is not specified then 
     * gets a System.Web.Http.HttpCookieCollection object of all cookies. 
     */
    public function getCookies($name = null, $default =  null) {
        if(null === $name){
            return $this->cookies;
        }
        return $this->cookies->get($name, $default);
    }
    
    /**
     * Gets the HTTP data transfer method.
     */
    public function getHttpMethod() : string {
        return $this->server->getString('REQUEST_METHOD')->toUpper();
    }
    
    /**
     * Gets the incoming HTTP request body.
     */
    public function getInputStream() : string {
        return $this->inputStream;
    }
    
    /**
     * Gets a boolean value that indicates if the request method was a GET.
     */
    public function isGet() : bool {
        if($this->getHttpMethod() == 'GET'){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a boolean value that indicates if the request method was a POST.
     */
    public function isPost() : bool {
        if($this->getHttpMethod() == 'POST'){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a boolean value that indicates if the request method was a PUT.
     */
    public function isPut() : bool {
        if($this->getHttpMethod() == 'PUT'){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a boolean value that indicates if the request method was a HEAD.
     */
    public function isHead() : bool {
        if($this->getHttpMethod() == 'HEAD'){
            return true;
        }
        return false;
    }
    
    /**
     * Gets a boolean value that indicates if the request method was a DELETE.
     */
    public function isDelete() : bool {
        if($this->getHttpMethod() == 'DELETE'){
            return true;
        }
        return false;
    }
    
    public function bind($object){
        if(is_object($object)){
            $properties = Obj::getProperties($object);

            $className = Str::set(get_class($object))->split("\\\\")->last()->toLower();
            $params = $this->getParams();

            foreach($params as $key => $value){
                if(Str::set($key)->startsWith($className)){
                    $len = count($className);
                    Obj::setPropertyValue($object, Str::set($key)->subString($len)->trim('_'), $value);
                }
            }
        }
    }
    
    /**
     * Magic method. Alias of getParams($name) method.
     */
    public function __get($name) {
        return $this->getParams($name);
    }
    
} 