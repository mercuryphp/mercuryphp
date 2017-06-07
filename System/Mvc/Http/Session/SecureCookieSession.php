<?php

namespace System\Mvc\Http\Session; 

class SecureCookieSession extends \System\Mvc\Http\Session\Session {

    public function __construct(){
        $this->name = session_name();
    }

    public function open(){
        $this->active = true;
        $dataHash = base64_decode($this->sessionId);
        $data = \System\Core\Str::set($dataHash)->getLastIndexOf(':');
        print_R($data);
        $this->collection = unserialize((string)$data);
    }

    public function write(){
        if($this->active){
            $data = serialize($this->collection);
            $dataHash = $data.':'.hash_hmac('sha256', $data, 'onceuptonatimeinmexicocity');
            $this->sessionId = base64_encode($dataHash);
        }
    }
}
