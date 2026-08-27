<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package cjw-brummen
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array<int, string> $classes Classes for the body element.
 *
 * @return array<int, string>
 */
function cjw_theme_body_classes($classes)
{
    // Adds a class of hfeed to non-singular pages.
    if (! is_singular()) {
        $classes[] = 'hfeed';
    }

    return $classes;
}
add_filter('body_class', 'cjw_theme_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function cjw_theme_pingback_header(): void
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'cjw_theme_pingback_header');
