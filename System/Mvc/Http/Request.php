<?php

namespace System\Mvc\Http;

final class Request {
    
    private $url;
    private $server = [];
    private $query = [];
    private $post = [];
    private $cookies;
    private $routeData;

    public function __construct(){
        $this->url = new Url();
        $this->server = $_SERVER;
        $this->query = $_GET;
        $this->post = $_POST;
        $this->cookies = new HttpCookieCollection($_COOKIE);

        $rawInput = file_get_contents("php://input");

        if($rawInput && $this->getServer('CONTENT_TYPE') == 'application/json'){
            $data = json_decode($rawInput, true);
            $this->post = array_merge($this->post, $data);
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

    public function getServer(string $name){
        if(array_key_exists($name, $this->server)){
            return $this->server[$name];
        }
        return false;
    }
}