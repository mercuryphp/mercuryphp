<?php

namespace System\Web\Mvc\ViewEngine;

use System\Core\Str;
use System\Collections\Dictionary;

class NativeView implements IView {
    
    protected $viewFilePattern = '/{namespace}/{module}/Views/{controller}/{action}';
    protected $viewFilePatternTokens = [];
    protected $layoutFile;
    protected $output = [];
    protected $params = [];
    protected $dynamicMethods = [];
    
    public function setViewFilePattern(string $viewFilePattern, array $viewFilePatternTokens = []){
        $this->viewFilePattern = $viewFilePattern;
        $this->viewFilePatternTokens = $viewFilePatternTokens;
    }
    
    public function setLayout(string $layoutFile = ''){
        $this->layoutFile = $layoutFile;
        return $this;
    }
    
    public function addMethod($name, $function){
        $this->dynamicMethods[$name] = $function;
    }
    
    public function addParam(string $name, $value){
        $this->params[$name] = $value;
    }

    public function renderBody(){
        if(isset($this->output['view'])){
            echo $this->output['view'];
        }else{
            throw new \RuntimeException("The renderBody() method can only be called from a layout file.");
        }
    }

    public function render(\System\Web\Mvc\ViewContext $viewContext) : string {
        
        $request = $viewContext->getHttpContext()->getRequest(); 
        $fileParams = $request->getRouteData()->toArray();

        if($viewContext->getViewName()){
            $fileParams['action'] = $viewContext->getViewName();
        }
        
        $file = Str::set($request->getApplicationPath())->append($this->viewFilePattern)->template(
            array_merge($this->viewFilePatternTokens,$fileParams),
            ['module' => 'lc.ucf', 'controller' => 'lc.ucf', 'action' => 'lc.ucf']
        )->replace('\.', '/')->append('.php');

        if(is_file(realpath($file))){
            extract($this->params);
            extract($viewContext->getParams());
            ob_start();
            
            include $file;
            $this->output['view'] = ob_get_clean();

            if($this->layoutFile){
                $layoutFile = $request->getApplicationPath().$this->layoutFile;
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
    
    public function __call($name, $arguments){
        if($this->dynamicMethods[$name] instanceof \Closure){
            return $this->dynamicMethods[$name]->call($this, ...$arguments);
        }
        elseif($this->dynamicMethods[$name] instanceof IViewMethod){
            return $this->dynamicMethods[$name]->getClosure()->call($this, ...$arguments);
        }
    }
    
    public function __get($name){
        if(array_key_exists($name, $this->params)){
            return $this->params[$name];
        }
    }
}