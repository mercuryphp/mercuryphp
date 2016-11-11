<?php

namespace System\Web\Mvc;

use System\Core\Str;
use System\Core\Obj;
use System\Core\Date;

class ObjectModelBinder {
    
    public function bind($type, $request, $paramName){
        switch($type){
            case 'System\Core\Date':
                return new Date($request->getParams($paramName, 'NOW'));
            case 'System\Core\Str':
                return new Str($request->getParams($paramName, ''));
            case 'System\Collections\Dictionary':
                return $request->getParams();
            case 'System\Web\Mvc\ActionArg':
                return new ActionArg($request->getParams($paramName, ''));
            default:
                try{
                    $model = Obj::getInstance($type);
                }catch(\ReflectionException $re){
                    throw new ModelBinderException($re->getMessage(), $re->getCode(), $re, (string)$type);
                }
                
                $properties = Obj::getProperties($model);

                foreach($properties as $property => $value){
                    $properties[$property] = $request->getParams(strtolower($property));
                }

                Obj::setProperties($model, $properties);
                return $model;
        }
    }
}