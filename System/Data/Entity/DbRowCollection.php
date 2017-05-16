<?php

namespace System\Data\Entity;

class DbRowCollection implements \IteratorAggregate {
    
    protected $rows = [];
    
    public function __construct(array $rows){
        $this->rows = $rows;
    }
    
    public function get($index){
        return $this->rows[$index];
    }
    
    public function rowIndex($name){
        foreach($this->rows as $idx => $row){
            $data = is_object($row) ? \System\Core\Obj::getProperties($row) : $row;
            
            if(is_array($data) && array_key_exists($name, $data)){
                unset($this->rows[$idx]);
                $this->rows[$data[$name]] = $row;
            }
        }
        return $this;
    }
    
    public function max($name){
        $max = null;
        foreach($this->rows as $idx => $row){
            $data = is_object($row) ? \System\Core\Obj::getProperties($row) : $row;
            
            if(is_array($data) && array_key_exists($name, $data)){
                if($data[$name] > $max){
                    $max = $data[$name];
                }
            }
        }
        return $max;
    }

    public function merge($data){
        $this->rows = array_merge($this->rows, $data);
        return $this;
    }

    public function hasIndex($index){
        if(array_key_exists($index, $this->rows)){
            return true;
        }
        return false;
    }

    public function getIterator(){
        return new \ArrayIterator($this->rows);
    }
}
