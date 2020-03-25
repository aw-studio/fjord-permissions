<?php

if(! function_exists('fjord_permissions_path')) {
    function fjord_permissions_path($path = '') {
        return realpath(__DIR__ . '/../../').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
