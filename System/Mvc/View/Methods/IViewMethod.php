<?php

namespace System\Mvc\View\Methods;

interface IViewMethod {
    public function getClosure() : \Closure;
}