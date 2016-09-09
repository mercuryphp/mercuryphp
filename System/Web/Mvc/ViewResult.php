<?php

namespace System\Web\Mvc;

class ViewResult implements IActionResult {
    
    protected $viewEngine;
    protected $viewContext;
    
    public function __construct($viewEngine, $viewContext){
        $this->viewEngine = $viewEngine;
        $this->viewContext = $viewContext;
    }
    
    public function execute(){
        return $this->viewEngine->render($this->viewContext);
    }
}