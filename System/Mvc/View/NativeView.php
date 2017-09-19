<?php

namespace System\Mvc\View;

use System\Core\Str;

class NativeView extends View {
    
    protected $rootDirectory;
    protected $viewDirectoryPattern = '{root}/{namespace}/{module}/Views/{controller}/{action}';
    protected $layout;
    protected $output;

    public function __construct(string $rootDirectory){
        $this->rootDirectory = $rootDirectory;
    }
    
    public function setViewDirectoryPattern(string $viewDirectoryPattern){
        $this->viewDirectoryPattern = $viewDirectoryPattern;
    }

    public function setLayout(string $layout){
        $this->layout = $layout;
    }
    
    public function includeFile(string $file){
        extract($this->params);

        $includeFile = Str::set($file)->tokens(['root' => $this->rootDirectory]);
        return include $includeFile;
    }

    public function renderBody(){
        if(isset($this->output['view'])){
            echo $this->output['view'];
        }else{
            throw new \RuntimeException("The renderBody() method can only be called from a layout file.");
        }
    }
    
    public function render(\System\Mvc\Http\HttpContext $httpContext, array $params = [], string $viewName = '') : string{
        $this->params = array_merge($this->params, $params);
        $this->params['environment'] = $httpContext->getEnvironment();
        $this->params['request'] = $httpContext->getRequest();
        $this->params['response'] = $httpContext->getResponse();
        $this->params['session'] = $httpContext->getSession();
        
        extract($this->params);

        $viewFile = (string)\System\Core\Str::set($this->viewDirectoryPattern)->tokens([
            'root' => $this->rootDirectory,
            'namespace' => Str::set($this->params['request']->getRouteData()->getNamespace())->replace('\\', '/'),
            'module' => Str::set($this->params['request']->getRouteData()->getModule())->toLower()->toUpperFirst(),
            'controller' => Str::set($this->params['request']->getRouteData()->getController())->toLower()->toUpperFirst(),
            'action' => Str::set($viewName)->toLower()->toUpperFirst()
        ])->append('.php');

        if(is_file($viewFile)){ 
            ob_start();
            include $viewFile;
            $this->output['view'] = ob_get_clean();
        }else{
            throw new ViewException(sprintf("The View '%s' does not exist.", $viewFile));
        }

        if($this->layout){
            $layoutFile = Str::set($this->layout)->tokens([
                'root' => $this->rootDirectory
            ]);
                
            if(is_file($layoutFile)){
                ob_start();
                include $layoutFile;
                $this->output['layout'] = ob_get_clean();
            }else{
                throw new ViewException(sprintf("The Layout View '%s' does not exist.", $layoutFile));
            }
        }
        
        if(isset($this->output['layout'])){
            return $this->output['layout'];
        }

        return $this->output['view'];
    }
}