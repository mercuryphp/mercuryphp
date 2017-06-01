<?php

namespace System\Mvc\View\Methods;

class Escape {

    public function execute(string $string){
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
    }
}