<?php

namespace System\Mvc\View;

class Layout{
    
    public function execute(string $layout = ''){
        $this->getViewEngine()->setLayout($layout);
        return true;
    }
}
