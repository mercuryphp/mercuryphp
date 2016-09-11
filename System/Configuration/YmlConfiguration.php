<?php

namespace System\Configuration;

class YmlConfiguration {
    
    public function __construct(){
        $lines = file('config.php');
        
        foreach($lines as $line){
            $line = trim($line);
            print substr($line, strlen($line)-1, 1) . "\n";
            if(substr($line, 0,-1)){
                
            }
        }
    }
}

