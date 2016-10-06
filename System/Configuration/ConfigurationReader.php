<?php

namespace System\Configuration;

use System\Core\Str;

final class ConfigurationReader extends Reader {
    
    public function __construct($configFile = null){
        $this->configFile = $configFile;
    }

    public function read() : \System\Collections\Dictionary {
        
        if(is_file($this->configFile)){
            $lines = file($this->configFile);
            $segments = [];

            foreach($lines as $line){
                $len = strlen($line);
                $count = strspn($line, " ");
                $segments[] = [$count, trim($line)];
            }
            
            $config = new \System\Collections\Dictionary();
            $paths = [];
            
            foreach($segments as $segment){
                
                $indent = ($segment[0] / 4 );
                $paths = array_splice($paths, 0, $indent);

                if(Str::set($segment[1])->endsWith(':')){
                    $paths[] = trim($segment[1], ':');
                }else{
                    
                    $configKey = join('.', $paths);
                    $keyVal = explode(':', $segment[1], 2);
                    
                    if(count($keyVal) == 2){
                        $key = trim($keyVal[0]);
                        $value = trim($keyVal[1]);
                        
                        if(Str::set($value)->startsWith('\[') && Str::set($value)->endsWith('\]')){
                            $value = str_getcsv(Str::set($value)->get('[', ']'));
                        }
                        elseif(Str::set($value)->startsWith('%') && Str::set($value)->endsWith('%')){
                            $value = $config->get((string)Str::set($value)->trim('%'));
                        }

                        if(Str::set($key)->startsWith('-')){
                            $key = trim($key, '-');
                            $array = $config->hasKey($configKey.'.'.$key) ? $config->get($configKey.'.'.$key) : [];
                            $array[] = $value;
                            $config->set($configKey.'.'.$key, $array);
                        }else{
                        
                            $config->set($configKey.'.'.$key, $value);
                        }
                        $key = '';
                    }
                }
            }
            
            if($config->hasKey('include.resources')){
                $resources = $config->get('include.resources');
                foreach($resources as $resource){
                    try{
                        $config->merge((new Configuration(new ConfigurationReader($resource)))->toArray());
                    }
                    catch(ConfigurationException $ce){
                        throw new ConfigurationException(sprintf("Unable to include resource file '%s'.", $resource));
                    }
                }
            }    
            return $config;
        }else{
            throw new ConfigurationException(sprintf("The configuration file '%s' does not exist.", $this->configFile));
        }
    }
    
}

