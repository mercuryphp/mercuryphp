<?php

namespace System\Web\Http\Session;

class FileSessionHandler {
    
    private $sessionPath;
    
    public function __construct(){
        $this->sessionPath = session_save_path();
    }

    public function read($id){
        try{
            return unserialize(file_get_contents($this->sessionPath.'/'.$id));
        }catch(\Exception $e){
            $sessionEntry = new \System\Web\Http\Session\SessionData();
            file_put_contents($this->sessionPath.'/'.$id, serialize($sessionEntry));
            return $sessionEntry;
        }
    }
    
    public function write($id, $sessionEntry){
        return file_put_contents($this->sessionPath.'/'.$id, serialize($sessionEntry));
    }
}
