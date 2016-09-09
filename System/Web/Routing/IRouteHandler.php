<?php

namespace System\Web\Routing;

interface IRouteHandler {
    public function execute(Route $route, \System\Web\HttpContext $httpContext) : bool;
}
