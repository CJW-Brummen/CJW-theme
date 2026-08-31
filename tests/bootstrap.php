<?php

/**
 * Lightweight WordPress shim for the theme's unit tests.
 *
 * Mirrors the plugin's tests/bootstrap.php: no database and no WordPress core
 * test installation, just enough of WordPress for the pure helpers in inc/ to
 * load and run.
 *
 * The theme's contract is that every helper degrades to a design default when
 * the cjw plugin is absent, and that stays the default here: cjw_summer_camp()
 * answers null unless a test installs a double. That is indistinguishable from
 * the function not existing, because cjw_brummen_camp() only asks whether the
 * call returns something.
 *
 * A test that needs the other half of the contract -- what the theme does with
 * a plugin that is present but has fields left blank -- installs a FakeCamp.
 * That path had never been covered, and it was where the theme printed camp
 * dates nobody had entered.
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

if (! defined('DAY_IN_SECONDS')) {
    define('DAY_IN_SECONDS', 86400);
}

if (! function_exists('wp_timezone')) {
    function wp_timezone(): DateTimeZone
    {
        return new DateTimeZone('Europe/Amsterdam');
    }
}

if (! function_exists('cjw_summer_camp')) {
    /**
     * The camp service a test installed, or null for the plugin-absent path.
     */
    function cjw_summer_camp(): ?object
    {
        return $GLOBALS['cjw_brummen_test_camp'] ?? null;
    }
}

if (! function_exists('wp_get_attachment_image_src')) {
    /**
     * Attachment ids a test has declared to exist; everything else is gone.
     *
     * @return array{0: string, 1: int, 2: int}|false
     */
    function wp_get_attachment_image_src(int $id, string $size = 'thumbnail')
    {
        $known = $GLOBALS['cjw_brummen_test_attachments'] ?? [];

        return in_array($id, $known, true) ? [ 'https://example.test/x.jpg', 900, 675 ] : false;
    }
}

if (! function_exists('number_format_i18n')) {
    /**
     * The Dutch decimal comma the site is written in.
     */
    function number_format_i18n(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', '.');
    }
}

if (! function_exists('add_action')) {
    /**
     * The hook API, reduced to what loading a file of helpers needs: nothing.
     * inc/blocks.php registers its hooks at file scope, and the tests below
     * call its functions directly rather than firing them.
     *
     * @param mixed ...$args Ignored.
     */
    function add_action(...$args): bool
    {
        unset($args);

        return true;
    }
}

if (! function_exists('add_filter')) {
    /**
     * @param mixed ...$args Ignored.
     */
    function add_filter(...$args): bool
    {
        unset($args);

        return true;
    }
}

require_once __DIR__ . '/support/TestCase.php';
require_once __DIR__ . '/support/FakeCamp.php';
require_once __DIR__ . '/../inc/front-page.php';
require_once __DIR__ . '/../inc/summer-camp.php';
require_once __DIR__ . '/../inc/verhuur.php';
require_once __DIR__ . '/../inc/blocks.php';
