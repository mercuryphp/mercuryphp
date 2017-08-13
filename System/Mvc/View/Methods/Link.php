<?php

namespace System\Mvc\View\Methods;

use System\Core\Obj;
use System\Core\Arr;
use System\Core\Str;

class Link{

    public function execute(string $title, $href = '', $params, array $attributes = []){
        
        if(is_object($params)){
            $params = Obj::getProperties($params);
        }
        
        $arr = new Arr($attributes);

        if(!$arr->hasKey('href')){
            $arr->add('href', $href);
        }

        $href = $arr->get('href');
        $href = Str::set($href)->tokens($params);
        
        $arr->set('href', $href);

        $control = Str::set('<a ');

        foreach($arr as $attribute=>$value){
            $control = $control->append($attribute)
                ->append('="')
                ->append($this->escape($value))
                ->append('" ');
        }
        return $control->append('>')->append($title)->append('</a>');
    }
}