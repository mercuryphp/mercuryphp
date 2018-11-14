<?php

namespace System\Mvc\View\Methods;

use System\Core\Obj;
use System\Core\Arr;
use System\Core\StrBuilder;

class TextArea {
    
    protected $escape;
    
    public function __construct($escape){
        $this->escape = $escape;
    }

    public function execute(string $name, $text = '', array $attributes = []){
        
        if(is_object($text)){
            try{
                $text = (string)Obj::getProperty($text, $name);
            }catch(\ReflectionException $re){
                throw new \RuntimeException(sprintf('Object "%s" does not have property "%s."', get_class($text), $name));
            }
        }

        $arr = new Arr($attributes);

        if(!$arr->hasKey('name')){
            $arr->add('name', $name);
        }
        if(!$arr->hasKey('id')){
            $arr->add('id', $arr->get('name'));
        }

        $control = new StrBuilder('<textarea ');

        foreach($arr as $attribute=>$value){
            $control->append($attribute)
                ->append('="')
                ->append($this->escape->execute($value))
                ->append('" ');
        }
        
        return $control->append('>')
            ->append($text)
            ->append('</textarea>');
    }
}