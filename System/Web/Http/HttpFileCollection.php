<?php

namespace System\Web\Http;

class HttpFileCollection implements \IteratorAggregate {
    
    protected $collection = [];
    
    public function __construct(array $files = []){
        foreach($files as $key => $file){
            $count = count($file['name']);
            
            for($idx =0; $idx < $count; $idx++){
                $this->collection[] = new HttpFile(
                    $key,
                    $file['name'][$idx],
                    $file['type'][$idx],
                    $file['tmp_name'][$idx],
                    $file['error'][$idx],
                    $file['size'][$idx]
                );
            }
        }
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
}