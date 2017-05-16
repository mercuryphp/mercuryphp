<?php

namespace System\Mvc;

interface IActionResult {
    public function execute() : string;
}