<?php

namespace System\Diagnostics;

final class Trace {
    
    private static $trace = [];

    public static function write(string $message, string $category = 'Application'){
        $time = microtime(true);
        self::$trace[] = new TraceItem($message, $category, $time);
    }
    
    public static function getData(){
        return self::$trace;
    }
}