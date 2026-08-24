# Tests

This theme uses PHPUnit, installed via Composer.

```bash
composer install
composer test
```

The suite runs against a lightweight WordPress shim, so it needs no database and
no full WordPress core test installation. Same setup as the `cjw` plugin.

Files:

- `phpunit.xml.dist` PHPUnit configuration
- `tests/bootstrap.php` lightweight WordPress shim and theme includes
- `tests/*Test.php` test cases

Current coverage focuses on:

- the palette math (hex parsing, shading, derived tokens)
- the plugin bridge's graceful degradation when the `cjw` plugin is absent
- squiggle title formatting and escaping

The shim deliberately does not define `cjw_summer_camp()`. The theme's contract
is that every plugin-backed helper falls back to a design default, so the
plugin-absent path is the path under test.
