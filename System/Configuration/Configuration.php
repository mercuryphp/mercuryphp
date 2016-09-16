<?php

namespace System\Configuration;

class Configuration implements IConfig {
    
    protected $config;
    
    public function __construct(string $configFile){
        
        if(is_file($configFile)){
            $lines = file($configFile);

            $tokens = [];
            $token = '';

            foreach($lines as $line){

                if(!trim($line)){
                    $tokens[] = ['type' => 'break'];
                    continue;
                }

                $len = strlen($line);

                for($i = 0; $i < $len; $i++){
                    $cc = $line[$i];

                    if($cc == ':'){
                        $tokens[] = ['type' => 'section', 'name' => $token];
                        $token = '';
                        continue;
                    }
                    if($cc == ';'){
                        $tokens[] = ['type' => 'value', 'value' => $token];
                        end($tokens);
                        $currentIndex = key($tokens); 
                        $tokens[$currentIndex-1]['type'] = 'key';
                        $token = '';
                        continue;
                    }

                    $token .= trim($cc);
                }
            }

            $this->config = new \System\Collections\Dictionary();
            $key = '';

            foreach($tokens as $idx => $token){

                if($token['type'] == 'section'){
                    $key .= $token['name'].'.';
                }
                elseif($token['type'] == 'key'){
                    if(isset($tokens[$idx+1])){
                        $nextToken = $tokens[$idx+1];
                        $this->config->set($key.$token['name'], $nextToken['value']);
                    }
                }
                elseif($token['type'] == 'break'){
                    $key = '';
                }
            }
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

