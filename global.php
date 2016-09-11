<?php

class Application extends System\Web\Mvc\HttpApplication {
    
    public function load(){
        $this->getRoutes()->add('{controller}/{action}', ['controller' => 'index', 'action' => 'index']);
    }
    
    public function error(Exception $e){
    	print_R($e);
    }
}
