<?php

namespace Controllers;

use System\Web\Mvc\ViewEngine\StringTemplate;

class IndexController extends \System\Web\Mvc\Controller {
    
    public function index(){
  

//print_R($this->getRequest()->getCookies()->get('Cookie')->getValue()); exit;
        //print_R(token_get_all(file_get_contents('/var/www/mercuryphp/Controllers/IndexController.php'))); exit;
        //$this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=merc','merc','Yellow@77');
        $db = new \System\Data\Database('driver=mysql;host=127.0.0.1;dbname=merc;uid=syed;pwd=Yellow77');
        
        $db->insert('class', [
            'class_name' => new \System\Data\DbFunction('now')
        ]);
        
        print_R($db->getProfiler()); exit;
        
        return $this->view([
            
        ]);
    }
}
