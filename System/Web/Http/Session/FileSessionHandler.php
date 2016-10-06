<?php

namespace System\Web\Http\Session;

class FileSessionHandler {
    
    private $sessionPath;
    
    public function __construct($sessionPath = null){
        if(null === $sessionPath){
            $this->sessionPath = session_save_path();
        }else{
            $this->sessionPath = $sessionPath;
        }
    }

    public function read($id){
        try{
            return unserialize(file_get_contents($this->sessionPath.'/'.$id));
        }catch(\Exception $e){
            $sessionData = new SessionData(); 
            try{
                file_put_contents($this->sessionPath.'/'.$id, serialize($sessionData));
            }
            catch (\ErrorException $se){
                throw new SessionException('Failed to write session data. Check that the session directory exists and is writeable.');
            }
            return $sessionData;
        }
    }
    
    public function write($id, $sessionData){
        return file_put_contents($this->sessionPath.'/'.$id, serialize($sessionData));
    }
}
