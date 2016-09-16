<?php

namespace System\Globalization;

class CultureInfo {
    
    protected $cultureName;
    protected $displayName;
    protected $dateTimeFormat;
    protected $numberFormat;

    /**
     * Initializes a new instance of CultureInfo with a culture name or a path to 
     * a culture file.
     */
    public function __construct($name){
        $this->cultureName = $name;
        $dataFile = (string)\System\Core\Str::set(dirname(__FILE__).'/Data/'.$name.'.xml')->replace('\\', '/');
        
        if(is_file($dataFile)){
            $xml = simplexml_load_file($dataFile);
        }elseif(is_file($name)){
            $name = \System\Core\Str::set($name)->replace('\\', '/');
            $xml = simplexml_load_file($name);
            $this->cultureName = (string)$name->get('/', '.', \System\Core\Str::LAST_FIRST);
        }else{
            throw new \Exception(sprintf("Culture '%s' is not supported", $name));
        }
         
        $this->displayName = (string)$xml->displayName;
        $this->dateTimeFormat = new DateTimeFormat($xml->datetime);
        $this->numberFormat = new NumberFormat($xml->numberFormat);
    }
    
    /**
     * Gets the culture name.
     */
    public function getName() : string {
        return $this->cultureName;
    }
    
    /**
     * Gets the culture display name.
     */
    public function getDisplayName() : string {
        return $this->displayName ;
    }
    
    /**
     * Gets a DateTimeFormat object for displaying dates and times.
     */
    public function getDateTimeFormat() : DateTimeFormat {
        return $this->dateTimeFormat;
    }

    /**
     * Gets a NumberFormat object for displaying numbers and currencies.
     */
    public function getNumberFormat() : NumberFormat {
        return $this->numberFormat;
    }
}