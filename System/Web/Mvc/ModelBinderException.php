<?php

namespace System\Web\Mvc;

class ModelBinderException extends \Exception {
    
    protected $modelName;
    
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, $modelName) {
        parent::__construct($message, $code, $previous);
        $this->modelName = $modelName;
    }
    
    public function getModelName(){
        return $this->modelName;
    }
}
