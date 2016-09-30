<?php

use System\Diagnostics\Trace;

final class System {
    public static function initialize(){

        set_error_handler(function($errno, $errstr, $errfile, $errline ){
            throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
        });
        
        $rootPath = str_replace('\\', '/', __DIR__);
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
            Trace::write('Application load()');
            $app->load();
            Trace::write('Application run()');
            $app->run();
        } catch (Exception $ex) {
            Trace::write('Application error()');
            $app->error($ex);
        }
        Trace::write('Application end()');
        $app->end(); Trace::t();
    } 
}

System::initialize();

?>
