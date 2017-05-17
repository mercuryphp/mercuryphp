<?php

namespace System\Core;

use System\Core\Str;

/**
 * An instance of the Configuration class can be used to read configuration data
 * from a file.
 */
class Configuration {
    
    protected $config = [];
    
    /**
     * Initializes an instance of Configuration with a filename.
     */
    public function __construct(string $file){
        try {
            $content = file_get_contents($file);
            $this->config = json_decode($content, true);

            if(json_last_error() > 0){
                throw new \RuntimeException(sprintf('An error occured in configuration file "%s" (%s).', $file, json_last_error_msg()));
            }
        }catch(\Exception $e){
            throw new \RuntimeException($e->getMessage());
        }
    }
    
    /**
     * Gets an item from the configuration file using a path notation.
     */
    public function get(string $path, $default = null){
        
        $config = explode('.', $path);
        $array = $this->config;
        
        foreach($config as $section){
            if(!array_key_exists($section, $array)){
                return $default;
            }
            $array = $array[$section];
        }
        return $array;
    }
    
    /**
     * Get the configuration data as an array.
     */
    public function toArray() : array{
        return $this->config;
    }
}