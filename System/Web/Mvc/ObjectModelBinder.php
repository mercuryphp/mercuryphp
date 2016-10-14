<?php

namespace System\Web\Mvc;

class ObjectModelBinder {
    
    public function bind($type, $request, $paramName){
        switch($type){
            case 'System\Core\Date':
                return new \System\Core\Date($request->getParams($paramName, 'NOW'));
            case 'System\Core\Str':
                return new \System\Core\Str($request->getParams($paramName, ''));
            case 'System\Collections\Dictionary':
                return $request->getParams();
        }
    }
}