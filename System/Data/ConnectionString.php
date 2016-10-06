<?php

namespace System\Data;

class ConnectionString {
    
    private $dsn;
    private $username;
    private $password;

    /**
     * Initializes an instance of ConnectionString with a DSN.
     */
    public function __construct(string $dsn){
        
        $params = \System\Core\Str::set($dsn)->split(';');
        
        foreach($params as $idx => $section){
            $keyValue = explode('=', $section, 2);
            if(count($keyValue) == 2){
                $params->removeAt($idx);
                $params[trim($keyValue[0])] = trim($keyValue[1]);
            }
        }
        
        $this->username = $params->get('uid');
        $this->password = $params->get('pwd');
        
        $params->removeAt('uid');
        $params->removeAt('pwd');
        
        $driver = '';
        $dsn = '';
        foreach($params as $param => $value){
            if($param == 'driver'){
                $driver = $value;
            }else{
                $dsn .= $param.'='.$value.';';
            }
        }
        $this->dsn = $driver.':'.$dsn;
    }

    /**
     * Gets the username for the connection string.
     */
    public function getUser() : string {
        return $this->username;
    }
    
    /**
     * Gets the password for the connection string.
     */
    public function getPassword() : string {
        return $this->password;
    }
    
    /**
     * Gets the DSN string.
     */
    public function getDsn() : string {
        return $this->dsn;
    }
    
    public function __debugInfo(){
        return [
            'dns' => $this->dsn
        ];
    }
}