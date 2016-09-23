<?php

class Application extends System\Web\Mvc\HttpApplication {
    
    public function load(){
        $this->getRoutes()->add('library/{className}', ['controller' => 'library', 'action' => 'index']);
        $this->getRoutes()->add('{controller}/{action}', ['controller' => 'index', 'action' => 'index']);
    }
    
    public function beforeAction(\System\Web\Mvc\Controller $controller) {
        $controller->setViewEngine(new \System\Web\Mvc\ViewEngine\StringTemplateView());
        //$controller->getViewEngine()->setLayout('/Views/Shared/Main.php');
    }


    public function error(Exception $e){
    	print_R($e);
    }
}
