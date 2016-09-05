<?php

class Application extends System\Web\HttpApplication {
    
    public function load(){
        $this->getRoutes()->add('{module}/{controller}/{action}');
    }
}
