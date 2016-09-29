<?php

namespace System\Data;

class ConnectionString {
    
    private $params;
    private $username;
    private $password;

    public function __construct(string $dsn){
        
        $this->params = \System\Core\Str::set($dsn)->split(';');
        
        foreach($this->params as $idx => $section){
            $keyValue = explode('=', $section, 2);
            if(count($keyValue) == 2){
                $this->params->removeAt($idx);
                $this->params[trim($keyValue[0])] = trim($keyValue[1]);
            }
        }
        
        $this->username = $this->params->get('uid');
        $this->password = $this->params->get('pwd');
        
        $this->params->removeAt('uid');
        $this->params->removeAt('pwd');
    }

    public function getUser(){
        return $this->username;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function getDsn(){
        $driver = '';
        $dsn = '';
        foreach($this->params as $param => $value){
            if($param == 'driver'){
                $driver = $value;
            }else{
                $dsn .= $param.'='.$value.';';
            }
        }
        return $driver.':'.$dsn;
    }
}
