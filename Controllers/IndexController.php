<?php

namespace Controllers;

class IndexController extends \System\Web\Mvc\Controller {
    
    public function index(){
        return $this->view(['uname' => 'Test']);
    }
}
