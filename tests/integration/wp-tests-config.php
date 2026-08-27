<?php

/**
 * Database and paths for the integration suite.
 *
 * Kept in step with the plugin's copy on purpose -- the two repositories share
 * a toolchain. Read from the environment so the same file serves a developer's
 * machine and CI, which have nothing in common: locally WordPress is the Local
 * by Flywheel install this theme already lives inside, reached over a unix
 * socket; in CI it is a checkout next to a MySQL service container.
 *
 * Every value has a local default, so `composer test:wp` works on a laptop
 * with no setup beyond a database that exists.
 */

declare(strict_types=1);

/**
 * Environment variable, or a default.
 */
function cjw_test_env(string $name, string $default): string
{
    $value = getenv($name);

    return is_string($value) && $value !== '' ? $value : $default;
}

// The WordPress the tests run against. Not a copy: the real thing, which is
// the entire point -- a stub cannot fail the way register_post_type() does.
define('ABSPATH', rtrim(cjw_test_env('WP_ROOT_DIR', dirname(__DIR__, 5)), '/') . '/');

define('DB_NAME', cjw_test_env('WP_DB_NAME', 'wordpress_test'));
define('DB_USER', cjw_test_env('WP_DB_USER', 'root'));
define('DB_PASSWORD', cjw_test_env('WP_DB_PASSWORD', 'root'));
define('DB_HOST', cjw_test_env(
    'WP_DB_HOST',
    'localhost:' . getenv('HOME') . '/Library/Application Support/Local/run/e7rdYNuFy/mysql/mysqld.sock'
));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// Its own prefix, in its own database. The suite drops and recreates every
// table it owns on each run, so it must never be pointed at a real site.
$table_prefix = 'wptests_';

define('WP_TESTS_DOMAIN', 'cjw.test');
define('WP_TESTS_EMAIL', 'admin@cjw.test');
define('WP_TESTS_TITLE', 'CJW Brummen integration tests');
define('WP_PHP_BINARY', cjw_test_env('WP_PHP_BINARY', 'php'));

define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', false);

// Keys and salts. Fixed rather than random so a run is reproducible.
foreach ([
    'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY',
    'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT',
] as $cjw_test_salt) {
    define($cjw_test_salt, 'cjw-integration-' . strtolower($cjw_test_salt));
}
