<?php

namespace System\Configuration;

class Configuration implements IConfig {
    
    protected $config;
    
    public function __construct(string $configFile){
        
        if(is_file($configFile)){
            $lines = file($configFile);
            $segments = [];

            foreach($lines as $line){

                $len = strlen($line);
                
                $count = strspn($line, " ");

                $segments[] = [$count, $line];
            } print_R($segments); 
            exit;
        }else{
            throw new ConfigurationException(sprintf("The configuration file '%s' does not exist.", $configFile));
        }
    }
    
    public function get($path, $default = null) : string {
        if($this->config->hasKey($path)){
            return $this->config->get($path);
        }
        if(null !== $default){
            return $default;
        }
    }
}

