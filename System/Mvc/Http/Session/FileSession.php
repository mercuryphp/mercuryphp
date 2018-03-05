<?php

namespace System\Mvc\Http\Session; 

class FileSession extends \System\Mvc\Http\Session\Session {

    protected $sessionPath;
    
    public function __construct(){
        $this->name = session_name();
        $this->sessionId = bin2hex(random_bytes(30));
        $this->sessionPath = session_save_path();
    }

    public function open(){
        $this->active = true;
        $sessionFile = $this->sessionPath . '/sess_' . $this->sessionId;
        
        if(file_exists($sessionFile)){
            $this->collection = unserialize(file_get_contents($sessionFile));
            
            if(!is_array($this->collection)){
                $this->collection = [];
            }
        }else{
            $this->sessionId = bin2hex(random_bytes(30));
        }
    }

    public function write(){
        if($this->active){
            $sessionFile = $this->sessionPath . '/sess_' . $this->sessionId;
            file_put_contents($sessionFile, serialize($this->collection));
        }
    }
}
