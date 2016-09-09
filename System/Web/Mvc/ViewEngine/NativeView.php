<?php

namespace System\Web\Mvc\ViewEngine;

class NativeView implements IView {
    
    public function render(\System\Web\Mvc\ViewContext $viewContext){
        print_R($viewContext); exit;
    }
}