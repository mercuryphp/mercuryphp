<?php

namespace System\Web\Mvc\ViewEngine;

use System\Core\Str;
use System\Collections\Dictionary;

class TemplateView extends View {
    
    public function render(\System\Web\Mvc\ViewContext $viewContext){
        
        $request = $viewContext->getHttpContext()->getRequest(); 

        $file = Str::set($this->getPath())->append($this->viewFilePattern)->template(
            (new Dictionary())
            ->merge($request->getRouteData()->toArray())
            ->toArray(),
            ['controller' => 'lc.ucf', 'action' => 'lc.ucf']
        )->append('.tpl');

        if(is_file(realpath($file))){
            new TemplateTokenizer($file);
        }
        exit;
        return $output;
    }
}