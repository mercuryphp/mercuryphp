<?php

namespace System\Core;

use System\Core\Str;

/**
 * An instance of the Configuration class can be used to read configuration data
 * from a file.
 */
class Configuration {
    
    protected $sections = [];
    
    /**
     * Initializes an instance of Configuration with a filename.
     */
    public function __construct(string $file){
        try {
            $lines = file($file);
        }catch(\Exception $e){
            throw new \RuntimeException($e->getMessage());
        }
        
        $tokens = [];
        $token = '';
        $quote = false;
        $array = false;
        
        foreach ($lines as $line=> $string){
            $len = strlen($string);
            if(substr($string,0,5) == '<?php'){
                continue;
            }
            for($idx=0; $idx < $len; $idx++){
                $currentChar = isset($string[$idx]) ? $string[$idx] : '';
                
                if($currentChar == '{' && $quote == false){
                    $tokens[] = ['T_OPEN_SECTION', trim($token), $line];
                    $token = '';
                    continue;
                }

                if($currentChar == ':' && $quote == false){
                    $tokens[] = ['T_KEY', trim($token), $line];
                    $token = '';
                    continue;
                }

                if($currentChar == ';' && $quote == false){
                    $tokens[] = ['T_VALUE', trim($token), $line];
                    $token = '';
                    continue;
                }

                if($currentChar == '}' && $quote == false){
                    $tokens[] = ['T_CLOSE_SECTION', null, $line];
                    $token = '';
                    continue;
                }
                
                if(($currentChar == '[' || $currentChar == ']') && $quote == false){
                    $array = $array ? false : true;
                }
                
                if($currentChar == '"'){
                    $prevChar = isset($string[$idx-1]) ? $string[$idx-1] : false;
                    if($quote && $prevChar == '\\'){
                        $token = substr($token, 0, -1) . $currentChar;
                        continue;
                    }
                    $quote = $quote ? false : true;
                    if(!$array){
                        continue;
                    }
                }

                $token .= $currentChar;
            }
        }
        
        $this->sections = [];
        $currentSection = '';
        $currentKey = '';
        
        foreach($tokens as $idx => $token){
            switch($token[0]){
                case 'T_OPEN_SECTION':
                    $currentSection = $token[1];
                    break;
                case 'T_CLOSE_SECTION':
                    $currentSection = '';
                    break;
                case 'T_KEY':
                    $currentKey = $token[1]; 
                    $nextToken = isset($tokens[$idx+1]) ? $tokens[$idx+1] : false; ;
                    if($nextToken){
                        if($nextToken[0] != 'T_VALUE'){
                            throw new \RuntimeException(sprintf("Configuration error near line %s. Property value not closed.", $token[2]));
                        }
                    }
                    break;
                case 'T_VALUE':
                    $prevToken = isset($tokens[$idx-1]) ? $tokens[$idx-1] : false; ;
                    if($prevToken){
                        if($prevToken[0] != 'T_KEY'){
                            throw new \RuntimeException(sprintf("Configuration error near line %s. Invalid property.", $token[2]));
                        }
                    }
                    
                    $value = $token[1];
                    
                    if(Str::set($value)->first()->equals('[') && Str::set($value)->last()->equals(']')){
                        $sections = json_decode($value);
                    }else{
                        $sections = $value;
                    }
                    
                    $segments = explode('.', $currentSection.'.'.$currentKey);
                    
                    foreach(array_reverse($segments) as $segment){
                        $sections = [$segment => $sections];
                    } 
                    $this->sections = array_merge_recursive($this->sections, $sections);
                    break;
            }
        }

        if(isset($this->sections['import']['files'])){
            $files = $this->sections['import']['files'];
            foreach($files as $file){
                $config = new Configuration($file);
                $this->sections = array_merge($this->sections, $config->toArray());
            }
            unset($this->sections['import']);
        }
    }
    
    /**
     * Gets an item from the configuration file using a path notation.
     */
    public function get(string $path){
        $sections = explode('.', $path);
        $array = $this->sections;
        foreach($sections as $section){
            if(!array_key_exists($section, $array)){
                return false;
            }
            $array = $array[$section];
        }
        return $array;
    }
    
    /**
     * Get the configuration data as an array.
     */
    public function toArray() : array{
        return $this->sections;
    }
}