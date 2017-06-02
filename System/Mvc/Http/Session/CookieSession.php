<?php

namespace System\Mvc\Http\Session; 

class CookieSession extends \System\Mvc\Http\Session\Session {

    public function __construct(){
        $this->name = session_name();
    }

    public function open(){
        $this->active = true;
        $this->collection = unserialize(base64_decode($this->sessionId));
    }

    public function write(){
        if($this->active){
            $this->sessionId = base64_encode(serialize($this->collection));
        }
    }
}
