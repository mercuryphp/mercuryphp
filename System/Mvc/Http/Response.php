<?php

namespace System\Mvc\Http;

final class Response {
    
    private $string;
    private $statusCode;
    private $headers = [];
    private $cookies;
    private static $statusCodes = array(
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );
    
    public function __construct(){
        $this->cookies = new HttpCookieCollection();
    }

    public function addHeader(string $header, string $value){
        $this->headers[$header] = $value;
    }

    public function setContentType(string $contentType, string $encoding = 'utf-8'){
        $this->addHeader('Content-type', $contentType . '; charset=' . $encoding); 
        return $this;
    }
    
    public function getContentType() : string{
        return $this->headers['Content-type'];
    }
    
    public function setContentLength(int $length){
        $this->addHeader('Content-length', $length); 
        return $this;
    }
    
    public function getContentLength() : int {
        return $this->headers['Content-length'];
    }
    
    public function setStatusCode(int $statusCode){
        if (isset(self::$statusCodes[$statusCode])){
            $this->statusCode = (int)$statusCode;
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            header($protocol.' '.self::$statusCodes[$statusCode]);
        }
        return $this;
    }
    
    public function getCookies(){
        return $this->cookies;
    }

    public function toJson($data, $options = null){
        $jsonData = json_encode($data, $options); 
        $this->setContentType('application/json')
            ->setContentLength(strlen($jsonData))
            ->write($jsonData);
    }

    public function write(string $string){
        $this->string = $string;
        return $this;
    }
    
    public function flush(){
        if (!headers_sent()){    
            foreach($this->cookies as $cookie){
                setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpires(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
            }
            foreach($this->headers as $header=>$value){
                header($header.':'.$value, true);
            }
            echo $this->string;
        }
        exit;
    }
}