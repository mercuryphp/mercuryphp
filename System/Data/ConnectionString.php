<?php

namespace System\Data;

class ConnectionString {
    
    private $params;
    
    public function __construct(string $dsn){
        
        $this->params = new \System\Collections\Dictionary([
            'driver' => '',
            'host' => '',
            'dbname' => '',
            'uid' => '',
            'pwd' => '',
            'charset' => ''
        ]);
        
        $sections = \System\Core\Str::set($dsn)->split(';');
        
        foreach($sections as $idx => $section){
            $keyValue = explode('=', $section, 2);
            if(count($keyValue) == 2){
                $this->params[trim($keyValue[0])] = trim($keyValue[1]);
            }
        }
        
        print_R($this->params); exit;;
    }
    
    public function getUid(){

            return $this->segments['pwd'];
        
    }


}
