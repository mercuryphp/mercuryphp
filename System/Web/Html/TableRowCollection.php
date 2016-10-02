<?php

namespace System\Web\Html;

class TableRowCollection implements \IteratorAggregate {
    
    protected $data;
    
    public function add(array $rowData){
        $this->data[] = $rowData;
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->data);
    }
}