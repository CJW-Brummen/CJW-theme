# CJW Brummen

The WordPress theme for [cjw-brummen.nl](https://www.cjw-brummen.nl) — the summer camps run by CJW Brummen.

It is a block theme in the practical sense rather than the technical one: a classic PHP theme that ships six dynamic blocks, three patterns and an editor stylesheet, so the front page is assembled in the block editor instead of in template files.

**The theme owns the design. The `cjw` plugin owns the camp.** Dates, the yearly theme, prices, the hero image, the signup call to action, sponsors and the registration form all live in the plugin, and the theme reads them through the bridge in `inc/summer-camp.php`. That split is deliberate: a camp year is data an organiser edits, not markup a developer deploys.

---

## Requirements

| | |
|---|---|
| WordPress | 6.6 or newer (tested to 7.1) |
| PHP | 8.1 or newer |
| Node | 22, for the Sass build |
| Composer | for the PHP toolchain |

The [`cjw` plugin](../../plugins/cjw) is not a hard requirement — every bridge helper falls back to a design default, and the theme never fatals without it. It is, however, where all the content comes from, so a site without it shows a well-styled page about nothing in particular.

## Getting started

```sh
composer install
npm install
npm run compile     # sass -> style.css, style-rtl.css, editor-style.css
```

`npm run watch` recompiles on save while you work.

## The build

Three stylesheets are **compiled from `sass/` and committed**, because WordPress reads `style.css` from disk and there is no build step on the server:

| File | Built from | Loaded by |
|---|---|---|
| `style.css` | `sass/style.scss` | the front end |
| `style-rtl.css` | `style.css`, via `rtlcss` | right-to-left locales |
| `editor-style.css` | `sass/editor-style.scss` | the block editor |

Because they are build outputs under version control, they can drift from their source. CI compiles the Sass and fails on any difference, so **run `npm run compile` and commit the result** whenever you touch `sass/`.

The Sass uses the module system (`@use`), not `@import`. Two things to know:

- `@use` is hoisted to the top of a file, so a loud `/* comment */` written directly above one is re-emitted before every module that also depends on it. Section banners belong inside the partial they label.
- A partial's CSS is emitted once, at the point of first use — so the order of `@use` rules is the order of the output.

## Layout of the theme

```
sass/
  abstracts/     variables and mixins, no output
  generic/       normalize, box-sizing
  base/          elements and typography
  components/    navigation, media, pages
  utilities/     accessibility, alignments
  cjw/           the actual design: tokens, header, footer, drawer,
                 front page, inner pages, blocks, buttons, fonts
inc/
  summer-camp.php     the plugin bridge — every helper degrades on its own
  blocks.php          the six cjw/* blocks and their render callbacks
  front-page.php      front page assembly
  editor-setup.php    editor styles, block styles, the cjw category
  template-tags.php   template helpers
  template-functions.php
  customizer.php      site identity only; camp content is the plugin's
  custom-header.php
patterns/        checklijst, cta-band, foto-met-tekst
page-templates/  met-feitenkaart.php
js/              theme.js (menu drawer), blocks.js (editor registration)
assets/fonts/    self-hosted woff2 subsets
```

There is no blog. Underscores' comment, archive and classic-editor styling has been removed, and the site is pages only.

## The blocks

Six dynamic blocks, all registered in `inc/blocks.php` and rendered server-side so they always reflect current plugin data:

| Block | What it is |
|---|---|
| `cjw/hero` | Opening section: camp photo, date badge, signup button |
| `cjw/intro` | Welcome text with the key camp facts |
| `cjw/jaarthema` | Yearly theme teaser with the countdown |
| `cjw/cards` | The three navigation cards |
| `cjw/photos` | The photo wall |
| `cjw/sponsors` | The sponsor wall |

Each is `multiple => false` and `reusable => false`: they are page furniture, not content blocks.

## Fonts

Nunito (variable, 400–900) and Amatic SC (400 and 700), self-hosted as subset woff2 with correct `unicode-range` and `font-display: swap`. The two faces above the fold — Nunito latin and Amatic SC 700 latin — are preloaded in `wp_head`; the rest are left to `unicode-range` to fetch only if a page needs them.

## Tests

```sh
composer test       # fast, no database, no WordPress
composer test:wp    # against a real WordPress
composer check      # audit, lint, phpcs, formatting, PHPStan level 6, tests
composer check:all  # the above plus the integration suite
npm run lint        # eslint + stylelint
```

The fast suite runs against a hand-written stand-in for WordPress in `tests/bootstrap.php`, and deliberately runs **with the `cjw` plugin absent** — the plugin-missing path is the one worth pinning.

The integration suite boots a real WordPress against a scratch database and asks the questions a stub cannot answer: did `add_theme_support()` take effect, does the menu location exist, do the stylesheets it points at resolve. It needs a database; `tests/integration/wp-tests-config.php` reads its settings from the environment and defaults to the Local by Flywheel install this theme lives in.

## Conventions

- WordPress coding standards, and PHPStan level 6 with no baseline. One error is suppressed, in `phpstan.neon.dist`, and the comment there explains why it is the stub that is wrong and not the code.
- Two prefixes are in use: `cjw_brummen_` for anything about this site's content, `cjw_theme_` for the WordPress plumbing Underscores generated. New code should use `cjw_brummen_`.
- Dutch in the interface, English in the code and comments.
- Never read camp data directly: go through `inc/summer-camp.php`, so the fallback stays in one place.

## Credits

Built on [Underscores](https://underscores.me/) by Automattic, and licensed under the GPL v2 or later — see `LICENSE`. Very little of the starter theme is left; what remains is normalize, the accessibility helpers, and the menu scaffolding.
