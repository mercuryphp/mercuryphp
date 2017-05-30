<?php

namespace System\Mvc\View\Edge;

class EdgeView extends \System\Mvc\View\NativeView {
    
    public function __construct(string $cacheDir){
        print $cacheDir;
    }

    public function render(\System\Mvc\Http\HttpContext $httpContext, array $params = [], string $viewName = '') : string{
        return "OK";
    }
}
