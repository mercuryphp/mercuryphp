<?php

namespace System\Core;

class Date extends \DateTime {
    
    protected static $dateFormat = 'Y-m-d H:i:s';
    protected static $timezone = null;
    
    public function __construct(string $time = '', $object = null) {
        parent::__construct($time, $object);
    }

    public function toString(string $format = '', $timezone = null){
        
        if(null !== $timezone){
            $this->setTimezone(new \DateTimeZone($timezone));
        }
        
        $format = $format ? $format : self::$dateFormat;
        return $this->format($format);
    }
    
    public static function now(){
        if(null !== self::$timezone){
            return new Date('now', new \DateTimeZone(self::$timezone));
        }
        return new Date();
    }
    
    public static function getDateRange(Date $date, int $days, string $format = 'Y-m-d') : array{
        $dates = [];
        for($d = 0; $d < $days; $d++){
            $dates[] = $date->modify('1 day')->toString($format);
        }
        return $dates;
    }
    
    public static function dateFormat($format){
        self::$dateFormat = $format;
    }
    
    public static function timezone($timezone){
        self::$timezone = $timezone;
    }
    
    public function __toString(){ print self::$timezone; exit;
        return $this->toString();
    }
}
