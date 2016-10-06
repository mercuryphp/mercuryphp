<?php

namespace System\Core;

class Date extends \DateTime {

    /**
     * Initializes a new instance of the Date class.
     */
    public function __construct($string, $timeZone = null){
        
        if($timeZone){
            $timeZone = new \DateTimeZone($timeZone);
        }
        
        try{
            parent::__construct($string, $timeZone);
        }catch(\Exception $e){
            throw new \Exception(sprintf('Failed to parse datetime string (%s).', $string));
        }
    }
    
    /**
     * Gets the year component of the date represented by this instance.
     */
    public function getYear() : int {
        return (int)$this->format('Y');
    }
    
    /**
     * Gets the month component of the date represented by this instance.
     */
    public function getMonth() : int {
        return $this->format('m');
    }
    
    /**
     * Gets the day component of the date represented by this instance.
     */
    public function getDay() : int {
        return $this->format('d');
    }
    
    /**
     * Gets the hour component of the date represented by this instance.
     */
    public function getHour() : int {
        return (int)$this->format('H');
    }
    
    /**
     * Gets the minute component of the date represented by this instance.
     */
    public function getMinute() : int {
        return $this->format('i');
    }
    
     /**
     * Gets the second component of the date represented by this instance.
     */
    public function getSecond() : int {
        return (int)$this->format('s');
    }
    
     /**
     * Gets the day index component of the date represented by this instance.
     */
    public function getDayIndex() : int {
        return (int)$this->format('N');
    }
    
     /**
     * Gets the week index component of the date represented by this instance.
     */
    public function getWeekIndex() : int {
        return (int)$this->format('W');
    }

    /**
     * Returns a new System.Std.Date that adds the value of $days to the 
     * value of this instance.
     */
    public function addDays(int $days){
        $this->modify('+'.$days.' day');
        return $this;
    }
    
    /**
     * Returns a new System.Std.Date that adds the value of $months to the 
     * value of this instance.
     */
    public function addMonths(int $months){
        $this->modify('+'.$months.' month');
        return $this;
    }
    
    /**
     * Returns a new System.Std.Date that adds the value of $years to the 
     * value of this instance.
     */
    public function addYears(int $years){
        $this->modify('+'.$years.' year');
        return $this;
    }
    
    /**
     * Returns a new System.Std.Date that adds the value of $hours to the 
     * value of this instance.
     */
    public function addHours(int $hours){
        $this->modify('+'.$hours.' hour');
        return $this;
    }
    
    /**
     * Returns a new System.Std.Date that adds the value of $minutes to the 
     * value of this instance.
     */
    public function addMinutes(int $minutes){
        $this->modify('+'.$minutes.' minute');
        return $this;
    }
    
    /**
     * Returns a new System.Std.Date that adds the value of $seconds to the 
     * value of this instance.
     */
    public function addSeconds(int $seconds){
        $this->modify('+'.$seconds.' second');
        return $this;
    }

    /**
     * Gets a formatted date string specified by $format. If $format is not specified,
     * then the default format from the environment setting is used.
     */
    public function toString(string $format = null, \System\Globalization\CultureInfo $cultureInfo = null) : string {

        if(null == $format){
            $format = 'yyyy-MM-dd HH:mm:ss';
        }
        
        if(null == $cultureInfo){
            $cultureInfo = new \System\Globalization\CultureInfo('en');
        }

        $len = strlen($format);
        $j = 0;
        $tokens = array();
        $array = array('d','M','y','H','m', 's');
        
        for($i=0; $i < $len; $i++){
            $char = $format[$i];

            if(in_array($char, $array)){
                if(isset($tokens[$j])){
                    $tokens[$j] .= $char;
                }else{
                    $tokens[$j] = $char;
                } 
            }else{
                $j++;
                $tokens[$j] = $char;
                $j++;
            }
        }

        $string = '';
        foreach($tokens as $token){
            $format = trim($token);
            
            switch ($format) {
                case 'd':
                    $string .= (int)$this->getDay();
                    break;
                case 'dd':
                    $string .= $this->getDay();
                    break;
                case 'ddd':
                    $string .= $cultureInfo->getDateTimeFormat()->getDayNames()->getShortName((int)$this->getDayIndex());
                    break;
                case 'dddd':
                    $string .= $cultureInfo->getDateTimeFormat()->getDayNames()->getFullName((int)$this->getDayIndex());
                    break;
                case 'M':
                    $string .= (int)$this->getMonth();
                    break; 
                case 'MM':
                    $string .= $this->getMonth();
                    break;
                case 'MMM':
                    $string .= $cultureInfo->getDateTimeFormat()->getMonthNames()->getShortName((int)$this->getMonth()-1);
                    break;
                case 'MMMM':
                    $string .= $cultureInfo->getDateTimeFormat()->getMonthNames()->getFullName((int)$this->getMonth()-1);
                    break;
                case 'yyyy':
                    $string .= $this->getYear();
                    break;
                case 'HH':
                    $string .= $this->getHour();
                    break;
                case 'mm':
                    $string .= $this->getMinute();
                    break;
                case 's':
                    $string .= $this->getSecond();
                    break;
                case 'ss':
                    $string .= $this->getSecond() < 10 ? '0'.$this->getSecond() : $this->getSecond();
                    break;
                default:
                    $string .= $token;
                    break;
            }
        }
        return $string; 
    }
    
    /**
     * Converts a date string to a System.Core.Date
     */
    public static function parse($string, $timeZone = null){ 
        if($string){
            return new Date($string, $timeZone);
        }
    }

    /**
     * Gets a Date object that is set to the current date and time.
     */
    public static function now($timeZone = null){
        return new Date('NOW', $timeZone);
    }
}