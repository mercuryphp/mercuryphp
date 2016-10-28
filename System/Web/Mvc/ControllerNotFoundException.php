<?php

namespace System\Web\Mvc;

class ControllerNotFoundException extends HttpException {
    public function __construct(\System\Web\Http\HttpContext $httpContext, $className){
        //$httpContext->getResponse()->setStatusCode(404)->flush();
        parent::__construct(sprintf("The controller '%s' does not exist.", ltrim($className,'.')));
    }
}
