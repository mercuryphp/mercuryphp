<?php

namespace System\Mvc\Http;

class HttpFile{
    
    protected $name;
    protected $type;
    protected $tmpName;
    protected $size;
    protected $error;
    
    public function __construct(array $properties){
        $this->name = $properties['name'];
        $this->type = $properties['type'];
        $this->tmpName = $properties['tmp_name'];
        $this->size = $properties['size'];
        $this->error = $properties['error'];
    }
    
    public function getFileName(){
        return $this->name;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function getTmpName(){
        return $this->tmpName;
    }
    
    public function getSize(){
        return $this->size;
    }
    
    public function getError(){
        return $this->error;
    }
    
    public function getFileExtension(){
        $pos = stripos($this->name, '.');
        if($pos >-1){
            return substr($this->name, $pos + 1);
        }
    }
    
    public function getData(){
        $fp = fopen($this->tmpName, "r");
        $data = fread($fp, filesize($this->tmpName));
        fclose($fp);
        return $data;
    }
    
    public function isValid(){
        return !$this->error ? true : false;
    }
}