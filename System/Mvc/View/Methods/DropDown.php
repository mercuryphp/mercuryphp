<?php

namespace System\Mvc\View\Methods;

use System\Core\Arr;
use System\Core\StrBuilder;

class DropDown{

    public function execute(string $name, array $source, string $dataValue, string $dataText, $selectedValue = null, array $attributes = []){

        $arr = new Arr($attributes);

        if(!$arr->hasKey('name')){
            $arr->add('name', $name);
        }
        if(!$arr->hasKey('id')){
            $arr->add('id', $arr->get('name'));
        }

        $control = new StrBuilder('<select ');

        foreach($arr as $attribute=>$value){
            $control = $control->append($attribute)
                ->append('="')
                ->append($this->escape($value))
                ->append('" ');
        }

        $control = $control->append('>');

        foreach($source as $row){
            
            if(is_object($row)){
                $row = \System\Core\Obj::getProperties($row);
            }

            if(!array_key_exists($dataValue, $row)){
                throw new \RuntimeException(sprintf('The supplied data source to DropDown::%s does not have property"%s."', $name, $dataValue));
            }
            
            if(!array_key_exists($dataText, $row)){
                throw new \RuntimeException(sprintf('The supplied data source to DropDown::%s does not have property"%s."', $name, $dataText));
            }
            
            if(is_object($selectedValue)){
                try{
                    $selectedValue = \System\Core\Obj::getProperty($selectedValue, $name);
                }catch(\ReflectionException $re){
                    throw new \RuntimeException(sprintf('Object "%s" does not have property "%s."', get_class($selectedValue), $name));
                }
            }
            
            $key = $row[$dataValue];
            $value = $row[$dataText];
            
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
    }
}