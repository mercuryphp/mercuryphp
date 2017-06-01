<?php

namespace System\Core;

class Date extends \DateTime {
    
    protected static $dateFormat = 'Y-m-d H:i:s';
    protected static $timezone = null;
    
    public function __construct(string $date = '', $object = null) {
        parent::__construct($date, $object);
    }

    public function toString(string $format = '', string $timezone = null){
        
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
        return new Date('now');
    }
    
    public static function parse($dateTime){
        if(null !== self::$timezone){
            return new Date($dateTime, new \DateTimeZone(self::$timezone));
        }
        return new Date($dateTime);
    }
    
    public static function parseArgs($year, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0){
        
        $date = "$year-$month-$day $hour:$minute:$second";
        
        if(null !== self::$timezone){ 
            return new Date($date, new \DateTimeZone(self::$timezone));
        }
        return new Date($date);
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
    
    public function __toString(){
        return $this->toString();
    }
}
