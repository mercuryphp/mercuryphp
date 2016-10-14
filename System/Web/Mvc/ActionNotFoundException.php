<?php

namespace System\Web\Mvc;

class ActionNotFoundException extends HttpException {
    
    /**
     * Initializes an instance of ActionNotFoundException with an instance of
     * HttpContext.
     * 
     * @param   System.Web.HttpContext $httpContext
     */
    public function __construct(\System\Web\Http\HttpContext $httpContext, $controllerName){
        $routeData = $httpContext->getRequest()->getRouteData();
        //$httpContext->getResponse()->setStatusCode(404)->flush();
        parent::__construct(sprintf("The action '%s:%s()' does not exist.", str_replace('\\', '.',$controllerName), $routeData->get('action')));
    }
}