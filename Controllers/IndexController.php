<?php

namespace Controllers;

class IndexController extends \System\Web\Mvc\Controller {
    
    public function index(){
  
$v1 = (float)	1475276114.9674;
$v2 = (float)	1475276114.9674;

//print $v1 - $v2; exit;
//print_R($this->getRequest()->getCookies()->get('Cookie')->getValue()); exit;
        //print_R(token_get_all(file_get_contents('/var/www/mercuryphp/Controllers/IndexController.php'))); exit;
        //$this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=merc','merc','Yellow@77');
        //$db = new \System\Data\Database('driver=mysql;host=127.0.0.1;dbname=merc;uid=syed;pwd=Yellow77');
        for($i=0; $i < 100000000; $i++){
            
        }
        return $this->view([
            
        ]);
    }
}
