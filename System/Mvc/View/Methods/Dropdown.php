<?php

namespace System\Mvc\View\Methods;

use System\Core\Arr;
use System\Core\Str;

class Dropdown implements IViewMethod {

    public function getClosure() : \Closure{
        return function(string $name, array $source = [], $selectedValue = null, array $attributes = []){

            $arr = new Arr($attributes);
            
            if(!$arr->hasKey('name')){
                $arr->add('name', $name);
            }
            if(!$arr->hasKey('id')){
                $arr->add('id', $arr->get('name'));
            }

            $control = Str::set('<select ');
            
            foreach($arr as $attribute=>$value){
                $control = $control->append($attribute)
                    ->append('="')
                    ->append($this->escape($value))
                    ->append('" ');
            }
            
            $control = $control->append('>');
            
            foreach($source as $key=>$value){
                $control = $control->append('<option value="')
                    ->append($this->escape($key))
                    ->append('"');
                
                if($selectedValue == $key){
                    $control = $control->append(' selected');
                }
                
                $control = $control->append('>')
                    ->append($this->escape($value))
                    ->append('</option>');
            }
            
            return $control->append('</select>');
        };
    }
}