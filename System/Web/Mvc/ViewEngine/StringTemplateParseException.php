<?php

namespace System\Web\Mvc\ViewEngine;

class StringTemplateParseException extends \Exception {
    
    public function __construct($message, $errorLine){
        parent::__construct(sprintf($message, $errorLine));
    }
}
