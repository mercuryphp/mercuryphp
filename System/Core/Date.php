<?php

namespace System\Core;

class Date extends \DateTime {
    
    protected static $dateFormat = 'Y-m-d H:i:s';
    protected static $timezone = null;
    
    public static function dateFormat($format){
        self::$dateFormat = $format;
    }
    
    public static function timezone($timezone){
        self::$timezone = $timezone;
    }

    public static function now(){
        return new Date();
    }
    
    public static function getDateRange(Date $date, int $days, string $format = 'Y-m-d') : array{
        $dates = [];
        for($d = 0; $d < $days; $d++){
            $dates[] = $date->modify('1 day')->toString($format);
        }
        return $dates;
    }

    public function toString(string $format = '', $timezone = null){
        
        if(null != $timezone){
            $this->setTimezone(new \DateTimeZone($timezone));
        }else{
            if(null != self::$timezone){
                $this->setTimezone(new \DateTimeZone(self::$timezone));
            }
        }
        
        $format = $format ? $format : self::$dateFormat;
        return $this->format($format);
    }
    
    public function __toString(){
        return $this->toString();
    }
}
