<?php

namespace System\Mvc\Http\Session; 

use System\Core\Str;

class SecureCookieSession extends \System\Mvc\Http\Session\Session {

    public function __construct(){
        $this->name = session_name();
    }

    public function open(){
        $this->active = true;
        $dataHash = base64_decode($this->sessionId);
        $data = Str::set($dataHash)->getLastIndexOf(':');
        $hash = Str::set($dataHash)->subString(Str::set($dataHash)->lastIndexOf(':')+1);

        if($hash == hash_hmac('sha256', (string)$data, 'onceuptonatimeinmexicocity')){
            $this->collection = unserialize((string)$data); 
        }
    }

    public function write(){
        if($this->active){
            $data = serialize($this->collection);
            $dataHash = $data.':'.hash_hmac('sha256', $data, 'onceuptonatimeinmexicocity');
            $this->sessionId = base64_encode($dataHash);
        }
    }
}
