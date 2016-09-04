<?php

class Core {
    public static function autoload(){
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
    }
}

Core::autoload();

print System\Core\Str::set('hello')->subString('e');


?>
