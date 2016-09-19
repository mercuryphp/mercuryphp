<?php

namespace System\Web\Mvc\ViewEngine;

abstract class View {
    
    protected $path;
    protected $viewFilePattern = '{namespace}/{module}/Views/{controller}/{action}';
    
    public function setPath(string $path){
        $this->path = $path;
    }
    
    public function getPath() : string {
        return $this->path;
    }
    
    public abstract function render(\System\Web\Mvc\ViewContext $viewContext);
}