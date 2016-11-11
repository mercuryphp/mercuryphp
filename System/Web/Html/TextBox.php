<?php

namespace System\Web\Html;

use System\Core\Str;
use System\Core\Obj;

class TextBox extends Element {
    
    protected $name;
    protected $value;
    protected $textMode;
    
    const Text = 1;
    const Password = 2;
    const TextArea = 3;
    
    public function __construct($name, $value, array $attributes = [], $textMode = TextBox::Text){
        $this->name = $name;
        $this->value = $value;
        $this->attributes = $attributes;
        $this->textMode = $textMode;
    }
    
    public function render(){
        $control = new Str();
        
        if(is_object($this->value)){
            $properties = Obj::getProperties($this->value);
            
            if(array_key_exists($this->name, $properties)){
                $this->value = $properties[$this->name];
            }
        }
        
        if($this->textMode == TextBox::Text || $this->textMode == TextBox::Password){
            $string = '<input type="{mode}" name="{name}" id="{id}" value="{value}" {attributes} />';
        }else{
            $string = '<textarea name="{name}" id="{id}" {attributes}>{value}</textarea>';
        }
        
        return $control->append($string)->template([
            'mode' => $this->textMode == 1 ? 'text' : 'password',
            'name' => $this->name,
            'value' => $this->escape($this->value),
            'id' => Str::set($this->name)->replace('\.', '_'),
            'attributes' => $this->renderAttributes()
        ]);
        
    }
}