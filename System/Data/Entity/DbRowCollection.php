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
    
    public function setRowIndex($field){
        $tmp = [];
        foreach($this->rows as $idx => $row){
            $data = is_object($row) ? \System\Core\Obj::getProperties($row) : $row;
            
            if(is_array($data) && array_key_exists($field, $data)){
                $tmp[$data[$field]] = $row;
            }
        }
        $this->rows = $tmp;
        unset($tmp);
        return $this;
    }
    
    public function max($field){
        $max = null;
        foreach($this->rows as $idx => $row){
            $data = is_object($row) ? \System\Core\Obj::getProperties($row) : $row;
            
            if(is_array($data) && array_key_exists($field, $data)){
                if($data[$field] > $max){
                    $max = $data[$field];
                }
            }
        }
        return $max;
    }
    
    public function where($field, $value){
        foreach($this->rows as $idx => $row){
            $data = is_object($row) ? \System\Core\Obj::getProperties($row) : $row;
            
            if(is_array($data) && array_key_exists($field, $data)){ 
                if($data[$field] != $value){
                    unset($this->rows[$idx]);
                }
            }
        } 
        return $this;
    }
    
    public function insert($index, $row){
        array_splice($this->rows, $index, 0, [$row]);
        return $this;
    }
    
    public function add($row){
        $this->rows[] = $row;
        return $this;
    }

    public function merge($data){
        $this->rows = array_merge($this->rows, $data);
        return $this;
    }

    public function hasIndex($index) : bool{
        if(array_key_exists($index, $this->rows)){
            return true;
        }
        return false;
    }
    
    public function getValues($field){
        $array = [];
        foreach($this->rows as $idx => $row){
            $data = is_object($row) ? \System\Core\Obj::getProperties($row) : $row;
            
            if(is_array($data) && array_key_exists($field, $data)){ 
                $array[] = $data[$field];
            }
        }
        return $array;
    }
    
    public function groupBy($field){
        $array = [];
        foreach($this->rows as $idx => $row){
            $data = is_object($row) ? \System\Core\Obj::getProperties($row) : $row;
            
            if(is_array($data) && array_key_exists($field, $data)){ 
                $array[$data[$field]][] = $row;
            }
        }
        return $array;
    }
    
    public function count() : int{
        return count($this->rows);
    }
    
    public function toArray() : array{
        return $this->rows;
    }

    public function getIterator(){
        return new \ArrayIterator($this->rows);
    }
}
