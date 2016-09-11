<?php

namespace System\Web\Mvc;

interface IActionResult {
    /**
     * Executes an empty ActionResult.
     */
    public function execute() : string;
}