<?php

namespace System\Web\Mvc\ViewEngine;

interface IViewMethod {
    public function getClosure() : \Closure;
}