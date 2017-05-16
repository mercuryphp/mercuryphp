<?php

namespace System\Mvc\View\Methods;

class Escape implements IViewMethod {

    public function getClosure() : \Closure{
        return function($string){
            return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        };
    }
}