<?php

namespace System\Mvc\View\Methods;

use System\Core\Arr;
use System\Core\StrBuilder;

class Textbox {

    public function execute(string $name, string $value = null, array $attributes = []){
        
        $arr = new Arr($attributes);

        if(!$arr->hasKey('type')){
            $arr->add('text', 'type');
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

        $control = new StrBuilder('<input ');

        foreach($arr as $attribute=>$value){
            $control->append($attribute)
                ->append('="')
                ->append($this->escape($value))
                ->append('" ');
        }
        
        return $control->append(' />');
    }
}