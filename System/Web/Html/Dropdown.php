<?php

namespace System\Web\Html;

use System\Core\Str;
use System\Core\Obj;

class Dropdown extends Element {
    
    protected $name;
    protected $datasource;
    protected $memberValue;
    protected $memberName;
    
    public function __construct($name, \Traversable $datasource, string $memberValue = '', string $memberName = '', array $attributes = []){
        $this->name = $name;
        $this->datasource = $datasource;
        $this->memberValue = $memberValue;
        $this->memberName = $memberName;
        $this->attributes = $attributes;
    }
    
    public function render(){
        $control = (new Str('<select name="{name}" id="{id}" {attributes}>'))->template([
            'name' => $this->name,
            'id' => Str::set($this->name)->replace('\.', '_'),
            'attributes' => $this->renderAttributes()
        ]);
        
        foreach($this->datasource as $key => $data){
            if(is_object($data)){
                $data = Obj::getProperties($data);
            }
            
            if(!$this->memberValue){
                $value = $key;
                $text = $key;
            }else{
                $value = $data[$this->memberValue];
                $text = $data[$this->memberName];
            }
            
            $control = $control->append('<option value="{value}">{text}</option>')->template(['value' => $value, 'text' => $text]);
        }
        $control = $control->append('</select>');
        return $control;
    }
}