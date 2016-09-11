<?php

namespace System\Web\Mvc\ViewEngine;

abstract class View {
    
    protected $viewPath;
    
    public function setViewPath(string $viewPath){
        $this->viewPath = $viewPath;
    }
    
    public function getViewPath() : string {
        return $this->viewPath;
    }
    
    public abstract function render(\System\Web\Mvc\ViewContext $viewContext);
}