<?php

namespace Controllers;

class IndexController extends \System\Web\Mvc\Controller {
    
    //@System.Web.Http.HttpMethod("get", "post")
    public function index(\Models\User $user){

        $db = new \Models\DataContext('driver=mysql;host=127.0.0.1;dbname=merc;uid=syed;pwd=Yellow77');

        $user = $db->getUsers()->find(37);
        $user->username = new \System\Data\DbFunction('NOW');
        $user->email = 'Test';
        
        
        $db->saveChanges();
print_R($db->getEntities());
       return $this->view();
    }
}
