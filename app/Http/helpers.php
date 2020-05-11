<?php

use Illuminate\Support\Facades\Request;

/**
 * Sets active class to link for current page
 *
 * @param string $path
 * @return string|null
 */
if (!function_exists('setActiveLink')) {
    function setActiveLink(string $path)
    {
        if (Request::is($path)) {
            return 'active';
        }

        return null;
    }
}
