<?php

namespace System\Core;

final class Convert {
    
    public static function toBoolean($string){
        if(is_string($string)){
            if(strtolower($string) == 'true'){
                return true;
            }
            elseif(strtolower($string) == 'false'){
                return false;
            }
            throw new \InvalidArgumentException(sprintf("Unable to convert '%s' to boolean", $string));
        }
    }
}