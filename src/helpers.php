<?php

use ExtPHP\DataDot\Dot;

if (! function_exists('dot')) {
    function dot($data) {
        return new Dot($data);
    }
}