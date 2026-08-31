<?php

/**
 * CJW Brummen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cjw-brummen
 */

if (! defined('CJW_BRUMMEN_VERSION')) {
    // Replace the version number of the theme on each release.
    define('CJW_BRUMMEN_VERSION', '1.1.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cjw_brummen_setup(): void
{
    /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on cjw-brummen, use a find and replace
        * to change 'cjw-brummen' to the name of your theme in all the template files.
        */
    load_theme_textdomain('cjw-brummen', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
    add_theme_support('title-tag');

    /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
    add_theme_support('post-thumbnails');

    /*
     * Without a size, 'post-thumbnail' resolves to the full-size original, so
     * a search result rendered the uploaded photograph at its native
     * dimensions -- 1707x2560 and 570 KB for one result -- with
     * fetchpriority="high" on top. Registering the size makes the featured
     * image a thumbnail again.
     */
    set_post_thumbnail_size(400, 300, true);

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        [
            'menu-1' => esc_html__('Hoofdmenu', 'cjw-brummen'),
        ]
    );

    // Polaroid crop for the kampplakboek photos on the front page.
    add_image_size('cjw-polaroid', 900, 675, true);

    /*
        * Switch default core markup for search form, comment form, and comments
        * to output valid HTML5.
        */
    add_theme_support(
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'cjw_theme_custom_background_args',
            [
                'default-color' => 'faf6ee',
                'default-image' => '',
            ]
        )
    );

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        [
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ]
    );
}
add_action('after_setup_theme', 'cjw_brummen_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cjw_theme_content_width(): void
{
    $GLOBALS['content_width'] = apply_filters('cjw_theme_content_width', 640);
}
add_action('after_setup_theme', 'cjw_theme_content_width', 0);

/**
 * Swap the html element's no-js class for js as early as possible, so the
 * stylesheet can fall back to the plain navigation when JavaScript is off.
 */
function cjw_brummen_js_detection(): void
{
    echo "<script>document.documentElement.classList.replace('no-js','js');</script>\n";
}
add_action('wp_head', 'cjw_brummen_js_detection', 0);

/**
 * Enqueue scripts and styles.
 */
function cjw_brummen_scripts(): void
{
    wp_enqueue_style('cjw-theme-style', get_stylesheet_uri(), [], CJW_BRUMMEN_VERSION);
    wp_style_add_data('cjw-theme-style', 'rtl', 'replace');
    wp_add_inline_style('cjw-theme-style', cjw_brummen_accent_inline_css());

    wp_enqueue_script('cjw-theme-js', get_template_directory_uri() . '/js/theme.js', [], CJW_BRUMMEN_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'cjw_brummen_scripts');

/**
 * Preloads the two faces the top of the page is drawn with.
 *
 * A woff2 referenced from a stylesheet is only discovered once that stylesheet
 * has been fetched and parsed, which puts the fetch a full round trip behind
 * where it could be -- and both of these are used by the first thing a visitor
 * sees, so the delay lands squarely on the largest contentful paint.
 *
 * Only the latin subsets, and only the weights above the fold: Nunito is a
 * variable font covering 400-900 in one file, and Amatic SC is only ever bold
 * up there. The 400-weight Amatic and both latin-ext files are deliberately
 * left to unicode-range, which is doing its job -- preloading a file the
 * browser then decides it does not need is worse than not preloading at all.
 *
 * @return void
 */
function cjw_brummen_preload_fonts(): void
{
    foreach ([ 'nunito-var-latin.woff2', 'amatic-sc-700-latin.woff2' ] as $font) {
        printf(
            '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
            esc_url(get_theme_file_uri('assets/fonts/' . $font))
        );
    }
}
add_action('wp_head', 'cjw_brummen_preload_fonts', 1);

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Front page (CJW Homepage design) settings and helpers.
 */
require get_template_directory() . '/inc/front-page.php';

/**
 * Bridge to the cjw-* plugins (source of truth for all camp data).
 */
require get_template_directory() . '/inc/summer-camp.php';

/*
 * Clears the cached sponsor wall whenever a sponsor changes.
 *
 * Saving covers editing, publishing, unpublishing and reordering. Trashing,
 * untrashing and deleting each need their own hook, because save_post fires
 * for none of them -- and a sponsor pulled from the wall has to leave it
 * straight away, not in up to a day's time.
 *
 * Registered here rather than in the bridge, which is a file of pure helpers:
 * that is what lets the fast test suite load it with no WordPress underneath.
 */
add_action('save_post_sponsor', 'cjw_brummen_flush_sponsors_cache');
add_action('deleted_post', 'cjw_brummen_flush_sponsors_cache');
add_action('trashed_post', 'cjw_brummen_flush_sponsors_cache');
add_action('untrashed_post', 'cjw_brummen_flush_sponsors_cache');

/**
 * The rental inventory, read from the plugin's verhuurmateriaal records.
 */
require get_template_directory() . '/inc/verhuur.php';

/*
 * The inventory is cached the same way and cleared on the same four events as
 * the sponsor wall above. An item that has gone out on loan is a number a
 * hirer is about to plan around, so it must leave the page on save, not within
 * a day.
 */
add_action('save_post_verhuurmateriaal', 'cjw_brummen_flush_rental_cache');
add_action('deleted_post', 'cjw_brummen_flush_rental_cache');
add_action('trashed_post', 'cjw_brummen_flush_rental_cache');
add_action('untrashed_post', 'cjw_brummen_flush_rental_cache');
add_action('wp_enqueue_scripts', 'cjw_brummen_rental_scripts');

/**
 * Block editor integration: theme.json palette sync, editor styles and
 * block patterns.
 */
require get_template_directory() . '/inc/editor-setup.php';

/**
 * Dynamic blocks that render the front-page sections (cjw/hero, ...).
 */
require get_template_directory() . '/inc/blocks.php';
