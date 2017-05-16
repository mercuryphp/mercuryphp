<?php

namespace System\Mvc\View\Methods;

use System\Core\Str;

class File implements IViewMethod {

    public function getClosure() : \Closure{
        return function(string $file, array $tokens = []){
            if(is_file($file)){
                $content = file_get_contents($file);
                return Str::set($content)->tokens($tokens)->toString();
            }
            throw new \System\Mvc\View\ViewException(sprintf("The file '%s' does not exist.", $file));
        };
    }
}