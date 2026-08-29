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

/**
 * Open Graph and Twitter Card tags.
 *
 * Nothing on this site emits them: there is no SEO plugin, and WordPress core
 * does not do social markup. Every link shared to WhatsApp, Facebook or a
 * parents' group therefore unfurled as a bare URL with no title, description or
 * picture — which is most of how a camp actually gets shared.
 *
 * Deliberately small, and deliberately easy to switch off: install a real SEO
 * plugin later and this steps aside rather than emitting a second, competing
 * set of tags.
 *
 * @return void
 */
function cjw_brummen_social_meta()
{
    /**
     * Filters whether the theme emits its own social tags.
     *
     * @param bool $enabled Whether to emit them.
     */
    if (! apply_filters('cjw_brummen_social_meta', true)) {
        return;
    }

    if (is_404() || is_search()) {
        return;
    }

    $title = wp_get_document_title();
    $url = home_url(add_query_arg([]));
    $description = '';
    $image = '';

    if (is_singular()) {
        $post_id = get_queried_object_id();
        $description = has_excerpt($post_id)
            ? get_the_excerpt($post_id)
            : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 32);
        $url = (string) get_permalink($post_id);

        if (has_post_thumbnail($post_id)) {
            $image = (string) get_the_post_thumbnail_url($post_id, 'large');
        }
    }

    if ('' === $image && is_front_page()) {
        $hero = cjw_brummen_hero_image_id();
        $image = $hero ? (string) wp_get_attachment_image_url($hero, 'large') : '';
    }

    if ('' === $description) {
        $description = (string) get_bloginfo('description', 'display');
    }

    $tags = [
        'og:type' => is_front_page() ? 'website' : 'article',
        'og:site_name' => get_bloginfo('name'),
        'og:locale' => get_locale(),
        'og:title' => $title,
        'og:url' => $url,
    ];

    if ('' !== $description) {
        $tags['og:description'] = $description;
    }

    if ('' !== $image) {
        $tags['og:image'] = $image;
    }

    foreach ($tags as $property => $content) {
        printf(
            '<meta property="%1$s" content="%2$s">' . "\n",
            esc_attr($property),
            esc_attr((string) $content)
        );
    }

    printf(
        '<meta name="twitter:card" content="%s">' . "\n",
        '' !== $image ? 'summary_large_image' : 'summary'
    );
}
add_action('wp_head', 'cjw_brummen_social_meta', 5);
