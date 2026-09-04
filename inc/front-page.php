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
        'hero_subtitle' => 'Een week tenten, bosspellen en kampvuur voor iedereen van 6 t/m 17 jaar.',
        'theme_title' => 'Level up!',
        'theme_text' => 'Dit jaar wordt het bos één groot spel: verzamel punten met je tentgroep, versla eindbazen bij het bosspel en unlock de bonte avond. Geen schermen nodig — jij bent de speler.',
        // Blank: without the plugin there is no registration form, so there is
        // nothing honest for a signup button to link to and the theme shows none.
        'signup_url' => '',
        'contact_email' => 'info@cjw-brummen.nl',
        'camp_age_range' => '6 t/m 17 jaar',
        'camp_location' => 'Landgoed Brockhausen, Stokkum',
        'home_intro_title' => 'Welkom bij |CJW Zomerkampen',
        'home_intro_text' => "Elke zomer strijken we met honderden kinderen en een berg tenten neer in de bossen van Brummen. Een week lang geen schermen maar bosspellen, zwemmen, kampvuur en vrienden voor het leven — helemaal georganiseerd door vrijwilligers die zelf ooit als kamper begonnen.\n\nOf je nu 6 bent of 17: er is een kamp dat bij je past. Slapen doe je in een tent, eten uit de grote keukentent, en moe word je vanzelf.",
        'card_zomerkamp_title' => 'Zomerkamp',
        'card_zomerkamp_text' => 'Hoe ziet een kampweek eruit? Alles over de leeftijdsgroepen, het programma en het leven tussen de tenten.',
        'card_info_title' => 'Praktische info',
        'card_info_text' => 'Paklijst, data, kosten, vervoer en alle antwoorden voor (bezorgde) ouders. Zo kom je goed voorbereid aan.',
        'card_signup_title' => 'Inschrijven',
        'card_signup_text' => 'Vol = vol! Schrijf je snel in voor kamp {jaar} en verzeker jezelf van een plekje in het bos.',
        'photos_title' => 'Zo ziet kamp |eruit',
        'photos_lead' => 'Geplakt in het kampplakboek — een greep uit vorige zomers.',
        'sponsors_title' => 'Bedankt!',
        'sponsors_lead' => 'Zonder onze sponsoren geen tenten, geen bus en geen marshmallows. Dankjewel aan alle bedrijven uit Brummen en omstreken.',
        'sponsors_cta_text' => 'Ook sponsor worden? Leuk! →',
        'footer_about' => 'Al generaties lang dé kampweek in de bossen van Brummen, gedragen door vrijwilligers.',
        'footer_org' => 'Stichting CJW · Brummen (Gelderland)',
        'cta_title' => 'Zin gekregen?',
        'cta_text' => 'Vol = vol, dus wacht niet te lang.',
    ];
}

/**
 * Fallback for wp_nav_menu() while no menu is assigned: the seven links
 * from the homepage design.
 *
 * @param array<string, mixed> $args wp_nav_menu() arguments.
 */
function cjw_brummen_menu_fallback($args = []): void
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
