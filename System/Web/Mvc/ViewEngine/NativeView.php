<?php

namespace System\Web\Mvc\ViewEngine;

class NativeView implements IView {
    
    protected $viewFilePattern = '{path}/{namespace}/{module}/Views/{controller}/{action}.php';
    protected $layoutFile;
    protected $output = [];
        
    public function render(\System\Web\Mvc\ViewContext $viewContext){
        
        $request = $viewContext->getHttpContext()->getRequest(); 
        
        $file = \System\Core\Str::set($this->viewFilePattern)->template(
            array_merge(['path' => $viewContext->getRootPath()], $request->getRouteData()->toArray()),
            ['controller' => 'lc.ucf', 'action' => 'lc.ucf']
        );

        $viewFile = realpath($file);
        
        if(is_file($viewFile)){
            extract($viewContext->getParams());
            ob_start();
            
            include $viewFile;
            $this->output['view'] = ob_get_clean();

            if($this->layoutFile){
                if(substr($this->layoutFile, 0, 1) == '~'){
                    $this->layoutFile = \System\Std\Environment::getRootPath() . substr($this->layoutFile, 1);
                }
                
                if (is_file($this->layoutFile)){
                    ob_start();
                    require_once $this->layoutFile;
                    $this->output['layoutFile'] = ob_get_clean();
                }else{
                    throw new ViewNotFoundException("The Layout file '%s' was not found", $this->layoutFile);
                }
            }

            if(isset($this->output['layoutFile'])){
                return $this->output['layoutFile'];
            }
            
            return $this->output['view'];
        }else{
            throw new ViewNotFoundException("The View '%s' was not found.", (string)$viewFile);
        }
    }
}