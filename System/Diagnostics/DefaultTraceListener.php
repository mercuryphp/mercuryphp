<?php

namespace System\Diagnostics;

class DefaultTraceListener extends TraceListener {
    
    public function write(){
        foreach ($this->data as $item){
            $item->getMessage();
        }
    }
}

