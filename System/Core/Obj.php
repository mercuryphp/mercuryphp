<?php

namespace System\Core;
 
final class Obj {

    /**
     * A variadic method that converts key/value arrays or objects to an object
     * specified by $toClass name.
     */
    public static function toObject(){
        $args = func_get_args();
        
        if(count($args) >=2){
            $className = '\\'.str_replace('.', '\\', $args[0]);
            $refClass = new \ReflectionClass($className);
            $toObjInstance = $refClass->newInstance();

            unset($args[0]);
            
            foreach($args as $arg){
                
                if(is_object($arg)){
                    $arg = Obj::getProperties($arg);
                }
                
                if(is_array($arg)){
                    foreach($arg as $propertyName=>$propertyValue){
                        if($refClass->hasProperty($propertyName)){
                            $property = $refClass->getProperty($propertyName);
                            $property->setAccessible(true);
                            $property->setValue($toObjInstance, $propertyValue);
                        }
                    }
                }
            }
            return $toObjInstance;
        }
    }
    
    /**
     * Sets the property values of an object using an array.
     */
    public static function setProperties($object, array $properties){
        
        if(!is_object($object)){
            throw new \RuntimeException(sprintf('Object::getProperties() expects parameter 1 to be object, %s given', gettype($object)));
        }
        
        $refClass = new \ReflectionObject($object);

        foreach($properties as $key=>$value){
            $property = $refClass->getProperty($key);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }
        return $object;
    }
    
    /**
     * Gets the properties of an object as an array.
     */
    public static function getProperties($object, $filter = null){
        
        if(!is_object($object)){
            throw new \RuntimeException(sprintf('Object::getProperties() expects parameter 1 to be object, %s given', gettype($object)));
        }
        
        if(is_null($filter)){
            $filter = \ReflectionProperty::IS_PUBLIC | 
                \ReflectionProperty::IS_PROTECTED | 
                \ReflectionProperty::IS_PRIVATE |
                \ReflectionProperty::IS_STATIC;
        }
        
        $refClass = new \ReflectionObject($object);
        $properties = $refClass->getProperties($filter);
        $array = array();

        foreach($properties as $property){
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
        }
        return $array;
    }
    
    /**
     * Sets the property value of an object.
     */
    public static function setPropertyValue($object, $propertyName, $value){
        
        if(!is_object($object)){
            throw new \RuntimeException(sprintf('Object::setPropertyValue() expects parameter 1 to be object, %s given', gettype($object)));
        }
        
        $refClass = new \ReflectionObject($object);

        if($refClass->hasProperty($propertyName)){
            $property = $refClass->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }
        return false;
    }
   
    /**
     * Gets the property value of an object.
     */
    public static function getPropertyValue($object, $propertyName){
        
        if(!is_object($object)){
            throw new \RuntimeException(sprintf('Object::getPropertyValue() expects parameter 1 to be object, %s given', gettype($object)));
        }
        
        $refClass = new \ReflectionObject($object);
        if($refClass->hasProperty($propertyName)){
            $property = $refClass->getProperty($propertyName);
            $property->setAccessible(true);
            return $property->getValue($object);
        }
        return false;
    }

    /**
     * Gets a new instance of a class.
     */
    public static function getInstance($name, array $args = null, $throwException = true){
        try{
            $name = str_replace(".", "\\", $name);
            $refClass = new \ReflectionClass($name);

            if(is_array($args)){
                $instance = $refClass->newInstanceArgs($args);
            }else{
                $instance = $refClass->newInstance();
            }
        }catch(\ReflectionException $rex){
            if($throwException){
                throw $rex;
            }
            return null;
        }
        return $instance;
    }
    
    public static function hasProperty($object, $property){
        $obj = new \ReflectionObject($object);
        return $obj->hasProperty($property);
    }
    
    public static function hasMethod($object, $method){
        $obj = new \ReflectionObject($object);
        return $obj->hasMethod($method);
    }
}