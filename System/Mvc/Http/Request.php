<?php

namespace System\Mvc\Http;

final class Request {
    
    private $url;
    private $server = [];
    private $query = [];
    private $post = [];
    private $cookies;
    private $files;
    private $routeData;

    public function __construct(){
        $this->url = new Url();
        $this->server = $_SERVER;
        $this->query = $_GET;
        $this->post = $_POST;
        $this->cookies = new HttpCookieCollection($_COOKIE);
        $this->files = new HttpFileCollection($_FILES);

        $rawInput = $this->getRawInput();

        if($rawInput && $this->getContentType() == 'application/json'){
            $data = json_decode($rawInput, true);
            if(is_array($data)){
                $this->post = array_merge($this->post, $data);
            }else{
                print_R($data);
            }
        } 
    }
    
    public function setRouteData(\System\Mvc\Routing\RouteData $routeData){
        $this->routeData = $routeData;
    }
    
    public function getRouteData() : \System\Mvc\Routing\RouteData{
        return $this->routeData;
    }

    public function getUrl() : Url{
        return $this->url;
    }
    
    public function setQuery(string $name, $value = null){
        $this->query[$name] = $value;
    }
    
    public function getQuery(string $name = '', $default = null){
        if(!$name){
            return $this->query;
        }
        elseif(array_key_exists($name, $this->query)){
            return $this->query[$name];
        }
        elseif(null !== $default){
            return $default;
        }
        return false;
    }
    
    public function setPost(string $name, $value = null){
        $this->post[$name] = $value;
    }
    
    public function getPost(string $name = '', $default = null){
        if(!$name){
            return $this->post;
        }
        elseif(array_key_exists($name, $this->post)){
            return $this->post[$name];
        }
        elseif(null !== $default){
            return $default;
        }
        return false;
    }
    
    public function getCookie(string $name = ''){
        if(!$name){
            return $this->cookies;
        }
        if($this->cookies->hasCookie($name)){
            return $this->cookies->get($name);
        }
    }
    
    public function getFiles(string $name = ''){
        if(!$name){
            return $this->files;
        }
        if($this->files->hasFile($name)){
            return $this->files->get($name);
        }
    }
    
    public function getParam(string $name = '', $default = null){
        $params = array_merge($this->query, $this->post, $this->routeData->toArray());
        if(!$name){
            return $params;
        }
        elseif(array_key_exists($name, $params)){
            return $params[$name];
        }
        elseif(null !== $default){
            return $default;
        }
        return false;
    }
    
    public function getHttpMethod(){
        return $this->getServer('REQUEST_METHOD');
    }
    
    public function getContentType() : string{
        return (string)\System\Core\Str::set($this->getServer('CONTENT_TYPE'))->getLastIndexOf(';');
    }
    
    public function getRawInput(){
        return file_get_contents("php://input");
    }

    public function getServer(string $name){
        if(array_key_exists($name, $this->server)){
            return $this->server[$name];
        }
        return false;
    }
    
    public function isQuery() : bool{
        if($this->getServer('REQUEST_METHOD') == 'GET'){
            return true;
        }
        return false;
    }
    
    public function isPost() : bool{
        if($this->getServer('REQUEST_METHOD') == 'POST'){
            return true;
        }
        return false;
    }
    
    public function isPut() : bool{
        if($this->getServer('REQUEST_METHOD') == 'PUT'){
            return true;
        }
        return false;
    }
    
    public function isDelete() : bool{
        if($this->getServer('REQUEST_METHOD') == 'DELETE'){
            return true;
        }
        return false;
    }
    
    public function isAjax() : bool{
        if($this->getServer('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest'){
            return true;
        }
        return false;
    }
    
    public function bindTo($object){
        $params = $this->getParam();
        
        foreach($params as $name => $value){
            if(is_scalar($value)){
                $value = strlen($value) > 0 ? $value : null; 
                \System\Core\Obj::setProperty($object, $name, $value);
            }
        }
    }
}
