<?php

namespace System\Diagnostics;

class DefaultTraceListener extends TraceListener {
    
    public function __construct(){
        $this->name = 'defult';
    }
    
    public function write(){
        $table = new \System\Web\Html\Table();
        foreach ($this->data as $item){
            $table->getRows()->add([
                $item->getCategory(),
                $item->getMessage(),
                $item->getStartTime()
            ]);
        }
        echo $table->render();
    }
}

