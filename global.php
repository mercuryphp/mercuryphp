<?php

class Application extends System\Web\HttpApplication {
    
    public function load(){
        $this->getRoutes()->add('{module}/{controller}/{action}');
    }
    
    public function error(Exception $e){
    	print_R($e);
    }
}
