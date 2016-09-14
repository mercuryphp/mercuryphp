<?php

namespace Controllers;

class LibraryController extends \System\Web\Mvc\Controller {
    
    protected $classList = [];
    protected $pdo;
    protected $library = [];
    
    public function load(){
        $this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=merc','root','');
        $stm = $this->pdo->query("SELECT * FROM class order by class_name");
        
        $classes = $stm->fetchAll(\PDO::FETCH_OBJ);
        $tmp = [];
        foreach($classes as $class){
            $arr = explode('.', $class->class_name);
            $className = array_pop($arr);
            $tmp[join('.', $arr)][] = ['url' => $class->url_key, 'name' => $className];
        }
        
        //print_R($tmp); exit;
        $this->library = $tmp;
    }
    
    public function index(){
        $request = $this->getRequest();

        $this->openDir('/var/www/mercuryphp/System');
        
        /*
        $this->pdo->query('TRUNCATE class');
        
        foreach($this->classList as $class){
            $stm = $this->pdo->query("INSERT INTO class (url_key, class_name) VALUES('" . strtolower($class) . "', '" . $class . "')");
        }
        */
        
        try{
            
            $stm = $this->pdo->query("SELECT * FROM class where url_key='" . $request->getUriSegments()->get(1) . "'");
            
            $class = $stm->fetch(\PDO::FETCH_OBJ);
            
            $ref = new \ReflectionClass(str_replace('.', '\\', $class->class_name));
            
            $methods = $ref->getMethods();
            
            $arrMethods = [];
            foreach($methods as $method){
                $params = $method->getParameters();
                
                $arrParams = [];
                foreach( $params as $param ){
                    $arrParams[] = ['type' => (string)$param->getType(), 'name' => $param->getName()];
                }
                $arrMethods[$method->getName()]['description'] = str_replace('@class', $class->class_name, $this->getDescription($method->getFileName(), $method->getName()));
                $arrMethods[$method->getName()]['modifiers'] = \Reflection::getModifierNames($method->getModifiers());
                $arrMethods[$method->getName()]['return'] = (string)$method->getReturnType();
                $arrMethods[$method->getName()]['params'] = $arrParams;
            }

            ksort($arrMethods);
            //print_R($arrMethods); exit;
            return $this->view([
                'library' => $this->library, 
                'className' => $class->class_name,
                'classDescription' => $ref->getDocComment(),
                'methods' => $arrMethods
            ]);
            
        }catch(\Exception $se){
            print $se->getMessage();
        }
    }
    
    private function getDescription($path, $method){

        if(is_file($path)){
            $lines = file($path);

            $comments = array();
            $commentBlock = false;

            foreach($lines as $idx => $line){
                $m = \System\Core\Str::set($line)->get('function ','(');

                if(trim($line) == '/**'){
                    $comments = array();
                    $commentBlock = true;
                }
                
                if(trim($line) == ""){
                    $comments = array();
                    $commentBlock = false;
                }

                if((string)($m) == 'unction '.$method){
                    $commentBlock = false;
                    break;
                }

                if($commentBlock){
                    $comments[] = $line;
                }
            }

            $tmp = array();
            foreach($comments as $line){

                $line = trim($line);

                if(substr($line, 2, 1) !='' && substr($line, 2, 1) != '@'){
                    if(isset($tmp['description'])){
                        $description = $tmp['description'];
                        $description .= ' ' . substr($line, 2);
                    }else{
                        $description = trim(substr($line, 2), '*');
                    }
                    $tmp['description'] = $description;
                }
            } 

            if (!isset($tmp['description'])){
                return '';
            }else{
                return $tmp['description'];
            }

        }else{
            return '';
        }
    }
    
    private function openDir($path){
        $dir = opendir($path);
        while (false !== ($entry = readdir($dir))) {
            if(substr($entry, 0,1) !='.'){
                
                if(is_dir($path.'/'.$entry)){
                    $this->openDir($path.'/'.$entry);
                }else{
                    $this->classList[] = trim(str_replace("/", ".", str_replace("/var/www/mercuryphp/", "", $path.'.'.$entry)), '.php');
                }
            }
        }
    }
}
