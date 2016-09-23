<?php

namespace Controllers;

use System\Web\Mvc\ViewEngine\StringTemplate;

class       IndexController extends \System\Web\Mvc\Controller {
    
    public function index(){
        
        //print_R(token_get_all(file_get_contents('/var/www/mercuryphp/Controllers/IndexController.php'))); exit;
        //$this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=merc','merc','Yellow@77');
        $this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=merc','syed','Yellow77');
        $stm = $this->pdo->query("SELECT * FROM class order by class_name");
        
        $classes = $stm->fetchAll(\PDO::FETCH_OBJ);
        $tmp = [];
        foreach($classes as $class){
            $arr = explode('.', $class->class_name);
            $className = array_pop($arr);
            $tmp[join('.', $arr)][] = ['url' => $class->url_key, 'name' => $className];
        }
        
        //print_R($tmp); exit;
        $tmp;

        //print_R($tmp); exit;
        
        $users = [
            ['a' => ['AAA', 'AAA']],
        ];
        
        return $this->view([
            'user' => ['profile' =>  ['first_name' => 'syed'] ],
            'library' => $tmp
        ]);
    }
}
