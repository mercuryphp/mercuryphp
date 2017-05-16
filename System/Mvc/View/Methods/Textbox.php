<?php

namespace System\Mvc\View\Methods;

use System\Core\Arr;
use System\Core\Str;

class Textbox implements IViewMethod {

    public function getClosure() : \Closure{
        return function(string $name, $value = null, array $attributes = []){

            $arr = new Arr($attributes);
            
            if(!$arr->hasKey('type')){
                $arr->add('type', 'text');
            }
            if(!$arr->hasKey('name')){
                $arr->add('name', $name);
            }
            if(!$arr->hasKey('id')){
                $arr->add('id', $arr->get('name'));
            }
            if(!$arr->hasKey('value')){
                $arr->add('value', $value);
            }

            $control = Str::set('<input ');
            
            foreach($arr as $attribute=>$value){
                $control = $control->append($attribute)
                    ->append('="')
                    ->append($this->escape($value))
                    ->append('" ');
            }
            return $control->append(' />');
        };
    }
}