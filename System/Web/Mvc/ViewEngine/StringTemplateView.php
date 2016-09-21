<?php

namespace System\Web\Mvc\ViewEngine;

use System\Core\Str;
use System\Collections\Dictionary;

class StringTemplateView extends View {

    public function render(\System\Web\Mvc\ViewContext $viewContext){
        
        $request = $viewContext->getHttpContext()->getRequest(); 

        $file = Str::set($this->getPath())->append($this->viewFilePattern)->template(
            (new Dictionary())
            ->merge($request->getRouteData()->toArray())
            ->toArray(),
            ['controller' => 'lc.ucf', 'action' => 'lc.ucf']
        )->append('.tpl');

        $fileData = file_get_contents($file);
        $template = new StringTemplate($fileData);
        
        return $template->render($viewContext->getParams());
    }
}