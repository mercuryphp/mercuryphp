<?php

namespace Controllers;

class IndexController extends \System\Web\Mvc\Controller {
    
    public function index(){
        $this->getHttpContext()->getSession()->set('ssd', 'blaal');
       return  "ff";
    }
}
