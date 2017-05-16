<?php

namespace System\Mvc\View\Methods;

use System\Core\Arr;
use System\Core\Str;

class Link implements IViewMethod {

    public function getClosure() : \Closure{
        return function(string $href, string $title, $params = null, array $attributes = []){

            $arr = new Arr($attributes);

            if(!$arr->hasKey('href')){
                $arr->add('href', $href);
            }
            
            $href = $arr->get('href');
            
            if(is_scalar($params)){
                $href = str_replace('{?}', $params, $href);
            }
            $arr->set('href', $href);
            
            $control = Str::set('<a ');
            
            foreach($arr as $attribute=>$value){
                $control = $control->append($attribute)
                    ->append('="')
                    ->append($this->escape($value))
                    ->append('" ');
            }
            return $control->append('>')->append($title)->append('</a>');
        };
    }
}