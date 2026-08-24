<?php

/**
 * Lightweight WordPress shim for the theme's unit tests.
 *
 * Mirrors the plugin's tests/bootstrap.php: no database and no WordPress core
 * test installation, just enough of WordPress for the pure helpers in inc/ to
 * load and run. The theme's contract is that every helper degrades to a design
 * default when the cjw plugin is absent, so the shim deliberately does *not*
 * define cjw_summer_camp() -- the plugin-absent path is the path under test.
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/../../../../');
}

if (! defined('CJW_BRUMMEN_VERSION')) {
    define('CJW_BRUMMEN_VERSION', '1.1.0');
}

if (! function_exists('__')) {
    function __(string $text, string $domain = ''): string
    {
        return $text;
    }
}

if (! function_exists('_n')) {
    function _n(string $single, string $plural, int $number, string $domain = ''): string
    {
        return 1 === $number ? $single : $plural;
    }
}

if (! function_exists('esc_html')) {
    function esc_html(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('esc_attr')) {
    function esc_attr(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('esc_url')) {
    function esc_url(string $url): string
    {
        return filter_var($url, FILTER_SANITIZE_URL) ?: '';
    }
}

if (! function_exists('esc_url_raw')) {
    function esc_url_raw(string $url): string
    {
        return $url;
    }
}

if (! function_exists('home_url')) {
    function home_url(string $path = ''): string
    {
        return 'https://cjw-brummen.test' . $path;
    }
}

require_once __DIR__ . '/support/TestCase.php';
require_once __DIR__ . '/../inc/front-page.php';
require_once __DIR__ . '/../inc/summer-camp.php';
