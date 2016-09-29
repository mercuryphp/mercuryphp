<?php

namespace System\Web\Mvc;

class ViewResult implements IActionResult {
    
    protected $viewEngine;
    protected $viewContext;
    
    public function __construct(ViewEngine\IView $viewEngine, ViewContext $viewContext){
        $this->viewEngine = $viewEngine;
        $this->viewContext = $viewContext;
    }
    
    public function execute() : string {
        return $this->viewEngine->render($this->viewContext);
    }
}