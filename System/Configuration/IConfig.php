<?php

namespace System\Configuration;

interface IConfig {
    public function get($path, $default = null);
}
