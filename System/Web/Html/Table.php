<?php

namespace System\Web\Html;

class Table {
    
    protected $rows;
    
    public function __construct(string $id = '', string $class = ''){
        $this->rows = new TableRowCollection();
    }
    
    public function getRows(){
        return $this->rows;
    }

    public function render(){
        $control = \System\Core\Str::set('<table border=1>');
        
        foreach ($this->rows as $row){
            $control = $control->append('<tr>');
            foreach ($row as $column){
                $control = $control->append('<td>'.$column.'</td>');
            }
            $control = $control->append('</tr>');
        }
        
        return $control;
    }
}