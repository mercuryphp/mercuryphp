<?php

namespace Controllers;

class IndexController extends \System\Web\Mvc\Controller {
    
    //@System.Web.Http.HttpMethod("get", "post")
    public function index(\Models\User $user){

        $db = new \Models\DataContext();
       
        $db->getUsers()->add($user);
        
        print_R($db->saveChanges()); exit;
       return $this->view();
    }
}
