<?php

/**
 * WordPress.com-specific functions and definitions
 *
 * This file is centrally included from `wp-content/mu-plugins/wpcom-theme-compat.php`.
 *
 * @package cjw-brummen
 */

/**
 * Adds support for wp.com-specific theme functions.
 *
 * @global array $themecolors
 */
function cjw_theme_wpcom_setup()
{
    global $themecolors;

    // Set theme colors for third party services.
    if (! isset($themecolors)) {
        // Whitelist wpcom specific variable intended to be overruled.
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
        $themecolors = [
            'bg' => '',
            'border' => '',
            'text' => '',
            'link' => '',
            'url' => '',
        ];
    }
}
add_action('after_setup_theme', 'cjw_theme_wpcom_setup');
