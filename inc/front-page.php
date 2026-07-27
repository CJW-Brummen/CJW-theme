<?php

/**
 * Front page defaults and the navigation fallback.
 *
 * All editable content lives in the cjw-* plugins (source of truth): camp
 * data and website settings in the cjw-summer-camp settings page, sponsors
 * in the ACF "Sponsor" post type. See inc/summer-camp.php for the bridge.
 * The values here are only the design defaults used when those plugins
 * are not active.
 *
 * @package cjw-brummen
 */

/**
 * Default values used when the cjw-* plugins are not active.
 *
 * @return array<string, string>
 */
function cjw_brummen_front_page_defaults()
{
    return [
        'accent' => '#7c4dff',
        'countdown_date' => '2026-07-18',
        'hero_badge' => '18 t/m 25 juli',
        'hero_title' => 'Level up je zomervakantie!',
        'hero_subtitle' => 'Een week tenten, bosspellen en kampvuur voor iedereen van 7 t/m 18 jaar.',
        'theme_title' => 'Level up!',
        'theme_text' => 'Dit jaar wordt het bos één groot spel: verzamel punten met je tentgroep, versla eindbazen bij het bosspel en unlock de bonte avond. Geen schermen nodig — jij bent de speler.',
        'signup_url' => '#inschrijven',
        'contact_email' => 'info@cjw-brummen.nl',
    ];
}

/**
 * Fallback for wp_nav_menu() while no menu is assigned: the seven links
 * from the homepage design.
 *
 * @param array<string, mixed> $args wp_nav_menu() arguments.
 */
function cjw_brummen_menu_fallback($args = [])
{
    $items = [
        [
            'label' => __('Home', 'cjw-brummen'),
            'href' => home_url('/'),
        ],
        [
            'label' => __('Zomerkamp', 'cjw-brummen'),
            'href' => '#zomerkamp',
        ],
        [
            'label' => __('Praktische info', 'cjw-brummen'),
            'href' => '#praktische-info',
        ],
        [
            'label' => __('Historie', 'cjw-brummen'),
            'href' => '#historie',
        ],
        [
            'label' => __('Verhuur', 'cjw-brummen'),
            'href' => '#verhuur',
        ],
        [
            'label' => __('Sponsoren', 'cjw-brummen'),
            'href' => '#sponsoren',
        ],
        [
            'label' => __('Contact', 'cjw-brummen'),
            'href' => '#contact',
        ],
    ];

    $menu_class = ! empty($args['menu_class']) ? $args['menu_class'] : 'menu';

    echo '<ul class="' . esc_attr($menu_class) . '">';
    foreach ($items as $item) {
        echo '<li class="menu-item"><a href="' . esc_url($item['href']) . '">' . esc_html($item['label']) . '</a></li>';
    }
    echo '</ul>';
}
