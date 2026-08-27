<?php

/**
 * Boots real WordPress with this theme active.
 *
 * The fast suite in tests/bootstrap.php deliberately runs with the cjw plugin
 * absent, because "every helper degrades to a design default when the plugin
 * is missing" is the theme's contract and that is the path worth pinning. This
 * suite asks the other question: with a real WordPress underneath, does the
 * theme actually register what it claims to -- menus, image sizes, supports,
 * block styles -- and do the template helpers survive a real query.
 */

declare(strict_types=1);

$cjw_tests_dir = __DIR__;
$cjw_root = dirname(__DIR__, 2);

require_once $cjw_root . '/vendor/autoload.php';

define('WP_TESTS_PHPUNIT_POLYFILLS_PATH', $cjw_root . '/vendor/yoast/phpunit-polyfills');
define('WP_TESTS_CONFIG_FILE_PATH', $cjw_tests_dir . '/wp-tests-config.php');

$cjw_wp_suite = $cjw_root . '/vendor/wp-phpunit/wp-phpunit';

require_once $cjw_wp_suite . '/includes/functions.php';

// A theme is activated, not loaded: the directory it lives in has to be
// registered before switch_theme() will find it.
tests_add_filter('setup_theme', static function () use ($cjw_root): void {
    register_theme_directory(dirname($cjw_root));
    switch_theme(basename($cjw_root));
}, 0);

require $cjw_wp_suite . '/includes/bootstrap.php';

require_once __DIR__ . '/support/IntegrationTestCase.php';
