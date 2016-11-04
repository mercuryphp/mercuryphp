<?php

namespace System\Web\Html;

abstract class Element {
    
    protected $attributes = [];
    
    protected function escape($value){
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
    }

    public function renderAttributes(){
        $str = new \System\Core\Str();
        
        foreach($this->attributes as $attribute=>$value){
            $str = $str->append($attribute)->append('="')->append($this->escape($value))->append('"');
        }
        return $str->trim();
    }

    public abstract function render();
}