<?php

namespace System\Diagnostics;

final class Trace {
    
    private static $trace = [];
    
    public static function write(string $message, string $category = 'Application'){
        self::$trace[] = new TraceItem($message, $category, microtime(true), 'GET');
    }
    
    public static function getData(){
        return self::$trace;
    }
}