<?php

namespace System\Data;

class Profiler implements \IteratorAggregate {
    
    protected $logs;
    protected $lastLogId = 0;
  
    public function start(){
        ++$this->lastLogId;
        $this->logs['LOG'.$this->lastLogId]['start_time'] = microtime(true);
    }
    
    public function log($log, array $params = array(), $type = null){

        $endTime = microtime(true);
        $id = 'LOG'.$this->lastLogId;
        
        if(isset($this->logs[$id])){

            $this->logs[$id] = array(
                'log' => $log,
                'start_time' => $this->logs[$id]['start_time'],
                'end_time' => $endTime,
                'duration' => $endTime - $this->logs[$id]['start_time'],
                'params' => $params,
                'type' =>  $type  
            );

            if(null === $type){
                $type = \System\Core\Str::set($log)->subString(0,6)->trim()->toUpper();
                $this->logs[$id]['type'] = (string)$type;
            }
        }
    }
    
    public function getIterator(){
        return new ArrayAccess($this->logs);
    }
}