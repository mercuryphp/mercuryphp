<?php

namespace System\Web\Mvc\ViewEngine;

interface IView {
    
    public function render(\System\Web\Mvc\ViewContext $viewContext);
}