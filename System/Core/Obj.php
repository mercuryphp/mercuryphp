<?php

namespace System\Core;

final class Obj {
    
    /**
     * Sets the property values for the gieven $object. The $object argument 
     * can be either a class name or an instance of a class.
     */
    public static function setProperties($object, array $properties = []){ 
        
        if(is_string($object)){
            $refClass = new \ReflectionClass($object);
            $object = $refClass->newInstance();
        }elseif(is_object($object)){
            $refClass = new \ReflectionObject($object);
        }else{
            throw new \RuntimeException(sprintf('Obj::setProperties() expects parameter 1 to be object or string.', gettype($object)));
        }

        foreach($properties as $key=>$value){
            if($refClass->hasProperty($key)){
                $property = $refClass->getProperty($key);
                $property->setAccessible(true);
                $property->setValue($object, $value);
            }
        }
        return $object;
    }
    
    /**
     * Gets the properties of an object as an array.
     * 
     * @param   object $object
     * @param   mixed $filter = null
     * @return  array
     */
    public static function getProperties($object, $filter = null) : array{

        if(is_string($object)){
            $refClass = new \ReflectionClass($object);
            $object = $refClass->newInstance();
        }elseif(is_object($object)){
            $refClass = new \ReflectionObject($object);
        }else{
            throw new \RuntimeException(sprintf('Obj::getProperties() expects parameter 1 to be object or string.', gettype($object)));
        }
        
        if(is_null($filter)){
            $filter = \ReflectionProperty::IS_PUBLIC | 
                \ReflectionProperty::IS_PROTECTED | 
                \ReflectionProperty::IS_PRIVATE |
                \ReflectionProperty::IS_STATIC;
        }
        
        $properties = $refClass->getProperties($filter);
        $array = array();

        foreach($properties as $property){
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
        }
        return $array;
    }
    
    /**
     * Gets a boolean value that determines if the specified $object has a method.
     */
    public static function hasMethod($object, string $method) : bool{
        $object = new \ReflectionObject($object);
        return $object->hasMethod($method);
    }
    
    /**
     * Invokes a method on an object. The $args argument can be used to provide 
     * method parameters.
     */
    public static function invokeMethod($object, string $method, $args = []){
        $refMethod = new \ReflectionMethod($object, $method);
        $params = $refMethod->getParameters();

        $actionArgs = [];
        foreach($params as $param){
            $defaultValue = $param->isOptional() ? $param->getDefaultValue() : null;
            $type = is_object($param->getClass()) ? 'object' : $param->getType();

            switch($type){
                case '';
                case 'string':
                case 'int':
                case 'float':
                case 'bool':
                    if(array_key_exists($param->name, $args)){
                        $actionArgs[] = $args[$param->name] !==null ? $args[$param->name] : $defaultValue;
                    }else{
                        $actionArgs[] = $defaultValue;
                    }
                    break;
                case 'object':
                        $type = (string)$param->getType(); 
                        if($type){
                            $actionArgs[] = Obj::setProperties($type, $args);
                        }
                    break; 
            }
        }
        return $refMethod->invokeArgs($object, $actionArgs);
    }

    public static function getAttributes($object, string $method) : array{
        if(is_string($object)){
            $refClass = new \ReflectionClass($object);
            $object = $refClass->newInstance();
        }elseif(is_object($object)){
            $refClass = new \ReflectionObject($object);
        }else{
            throw new \RuntimeException(sprintf('Obj::getAttributes() expects parameter 1 to be object or string.', gettype($object)));
        }
        
        $lines = Str::set($refClass->getMethod($method)->getDocComment())->split("\\n");
        $attributes = [];
        
        foreach($lines as $line){
            if(Str::set($line)->trim(" *")->first() == '@'){
                $attribute = Str::set($line)->get('@', '(');
                $args = Str::set($line)->get('(', ')');
                $attributes[(string)$attribute->replace('.', '\\')] = json_decode('['.(string)$args.']');
            }
        }
        return $attributes;
    }
    
    /**
     * Gets the filename of a class/object. The $object argument can be either a 
     * class name or an instance of a class.
     * Throws RuntimeException if $object is not a string or an object.
     */
    public static function getFileName($object){
        if(is_string($object)){
            $refClass = new \ReflectionClass($object);
            $object = $refClass->newInstance();
        }elseif(is_object($object)){
            $refClass = new \ReflectionObject($object);
        }else{
            throw new \RuntimeException(sprintf('Obj::getFileName() expects parameter 1 to be object or string, %s given.', gettype($object)));
        }
        
        return $refClass->getFileName();
    }
    
    /**
     * Gets a new instance of a class.
     */
    public static function getInstance(string $name, array $args = array()){
        $name = str_replace('.', '\\', $name);
        $refClass = new \ReflectionClass($name);

        if(count($args) > 0){
            return $refClass->newInstanceArgs($args);
        }else{
            return $refClass->newInstance();
        }
    }
}