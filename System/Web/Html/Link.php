<?php

namespace System\Web\Html;

class Link extends Element {
    
    protected $title;
    protected $href;
    protected $params;
    
    public function __construct($title, $href, $params = null, array $attributes = []){
        $this->title = $title;
        $this->href = $href;
        $this->params = $params;
        $this->attributes = $attributes;
    }

    public function render(){
        $control = new \System\Core\Str();
        
        if(is_object($this->params)){
            $params = \System\Core\Obj::getProperties($this->params);
        }else{
            $params = is_array($this->params) ? $this->params : [];
        }
        
        foreach($params as $param => $value){
            $this->href = str_replace('@'.$param, $value, $this->href);
        }

        return $control->append('<a href="{href}"{attributes}>{title}</a>')->template([
            'href' => $this->href,
            'attributes' => $this->renderAttributes(),
            'title' => $this->title
        ]);
    }
}