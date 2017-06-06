<?php

namespace System\Mvc\View\Methods;

use System\Core\Obj;
use System\Core\Arr;
use System\Core\StrBuilder;

class TextBox {

    public function execute(string $name, $text = '', array $attributes = [], string $type = 'text'){
        
        if(is_object($text)){
            try{
                $text = Obj::getProperty($text, $name);
            }catch(\ReflectionException $re){
                throw new \RuntimeException(sprintf('Object "%s" does not have property "%s."', get_class($text), $name));
            }
        }
        
        $arr = new Arr($attributes);

        if(!$arr->hasKey('type')){
            $arr->add('type', $type);
        }
        if(!$arr->hasKey('name')){
            $arr->add('name', $name);
        }
        if(!$arr->hasKey('id')){
            $arr->add('id', $arr->get('name'));
        }
        if(!$arr->hasKey('value')){
            $arr->add('value', $text);
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