<?php

namespace System\Web\Mvc;

class ActionArg extends \System\Collections\Dictionary {
   
    public function __construct($value){ 
        $string = base64_decode($value);
        if($string){
            try{
                $array = unserialize($string);
                if(is_array($array)){
                    parent::__construct($array);
                }
            } catch (\Exception $e){
                throw new ModelBinderException(sprintf("Model binding failed. ActionArg expects a valid base64 encoded string. (%s).", $e->getMessage()));
            }
        }
    }
}