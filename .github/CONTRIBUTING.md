# Contributing

The CJW Brummen site is two repositories that are deliberately kept on the same
toolchain:

| Repository | Directory | What it is |
| --- | --- | --- |
| `CJW-theme` | `wp-content/themes/cjw-theme` | the theme: markup, styles, blocks |
| `CJW-plugin` | `wp-content/plugins/cjw` | the plugin: camp settings, form builder, shared services |

The plugin owns the data, the theme renders it. The theme reads everything
through `cjw_summer_camp()` / `CJW_Summer_Camp_Service` and falls back to design
defaults when the plugin is not active, so it never fatals on its own.

`.github/dependabot.yml`, `.github/workflows/quality.yml`, `.editorconfig`,
`.php-cs-fixer.dist.php` and the `rules` block of `.stylelintrc.json` are
identical in both repositories on purpose. **Change one, change the other.**

## Setup

```bash
composer install
npm ci
```

Requires PHP 8.5+ and Node 22+.

## Checks

Both repositories expose the same commands, and CI runs exactly these:

```bash
composer check        # security, lint, phpcs, format:check, analyze, test
npm run lint          # eslint + stylelint
```

Individually:

| Command | What it does |
| --- | --- |
| `composer security` | audits `composer.lock` against the advisory database |
| `composer lint` | PHP syntax check on every file |
| `composer phpcs` | WordPress coding standards + PHP compatibility |
| `composer phpcbf` | auto-fixes what `phpcs` can fix |
| `composer format` | applies the PHP-CS-Fixer ruleset |
| `composer format:check` | reports formatting drift without writing |
| `composer analyze` | PHPStan, level 3 |
| `composer test` | PHPUnit, against a lightweight WordPress shim |
| `composer make-pot` | regenerates the translation template |
| `npm run lint:fix` | auto-fixes eslint + stylelint findings |

Formatting is split on purpose: PHP-CS-Fixer owns formatting, PHPCS owns
WordPress safety and compatibility. The formatting sniffs are silenced in
`phpcs.xml.dist` so the two never fight. Don't re-enable them without reading
the comments in `.php-cs-fixer.dist.php` first — the templates mix PHP with
inline HTML and several PER-CS rules mangle them.

## Theme-only: styles

`style.css`, `style-rtl.css` and `editor-style.css` are **compiled output** and
are committed. After touching anything under `sass/`:

```bash
npm run compile       # compile:css + compile:rtl
```

The theme header (Theme Name, Version, …) lives in `sass/style.scss`, not in
`style.css`. Bump `CJW_BRUMMEN_VERSION` in `functions.php` to match when you
change the version.

## Pull requests

- Branch off `main`.
- `composer check` and `npm run lint` must pass; CI runs the same commands.
- Dependency bumps are Dependabot's job. Minor and patch bumps arrive grouped
  once a week; majors and security fixes arrive on their own.

Both repositories are licensed under the
[GNU General Public License v2 (or later)](LICENSE).
