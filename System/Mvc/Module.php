<?php

namespace System\Mvc;

abstract class Module {
    
    public function load(Controller $controller){}
    
    public function unload(Controller $controller){}
}