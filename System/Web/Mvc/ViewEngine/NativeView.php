<?php

namespace System\Web\Mvc\ViewEngine;

use System\Core\Str;
use System\Collections\Dictionary;

class NativeView extends View {
    
    protected $viewFilePattern = '{namespace}/{module}/Views/{controller}/{action}.php';
    protected $layoutFile;
    protected $output = [];
    
    public function setLayout(string $layoutFile = ''){
        $this->layoutFile = $layoutFile;
        return $this;
    }

    public function renderBody(){
        if(isset($this->output['view'])){
            echo $this->output['view'];
        }else{
            throw new \RuntimeException("The renderBody() method can only be called from a layout file.");
        }
    }

    public function render(\System\Web\Mvc\ViewContext $viewContext){
        
        $request = $viewContext->getHttpContext()->getRequest(); 

        $file = Str::set($this->getPath())->append($this->viewFilePattern)->template(
            (new Dictionary())
            ->merge($request->getRouteData()->toArray())
            ->toArray(),
            ['controller' => 'lc.ucf', 'action' => 'lc.ucf']
        );

        
        if(is_file(realpath($file))){
            extract($viewContext->getParams());
            ob_start();
            
            include $file;
            $this->output['view'] = ob_get_clean();

            if($this->layoutFile){
                $layoutFile = $this->getPath().$this->layoutFile;
                if (is_file(realpath($layoutFile))){
                    ob_start();
                    include $layoutFile;
                    $this->output['layoutFile'] = ob_get_clean();
                }else{
                    throw new ViewNotFoundException("The Layout file '%s' was not found", $layoutFile);
                }
            }

            if(isset($this->output['layoutFile'])){
                return $this->output['layoutFile'];
            }
            
            return $this->output['view'];
        }else{
            throw new ViewNotFoundException("The View '%s' was not found.", (string)$file);
        }
    }
}