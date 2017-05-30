<?php

namespace System\Mvc\Routing;

interface IRouteHandler {
    
    public function execute(\System\Mvc\Http\HttpContext $request, string $routePattern, array $defaults = []);
}
