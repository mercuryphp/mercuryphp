<?php

class Core {
    public static function init(){
        $rootPath = __DIR__;
        $autoload = [
            $rootPath, 
            $rootPath.'/Vendors'
        ];
        
        if(is_file('autoload.php')){
            $autoload = file('autoload.php');
        }

        foreach($autoload as $directory){
            spl_autoload_register(function($class) use ($directory){
                $file = str_replace(["\\", "_"], "/", $directory.'/'.$class).'.php';
                if (is_file($file)){ 
                    include $file;
                }
            });
        }
        
        include 'global.php';

        $app = new Application($rootPath);
        
        try{
            $app->load();
            $app->run();
        } catch (Exception $ex) {
        	$app->error($ex);
        }
    }
}

Core::init();

?>
