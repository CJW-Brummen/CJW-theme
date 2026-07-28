<?php

/**
 * Bridge to the cjw-* plugins: the plugins are the source of truth.
 *
 * All camp data (dates, yearly theme, hero, colors, prices, signup CTA)
 * is read from the CJW_Summer_Camp_Service provided by the cjw-common /
 * cjw-summer-camp plugins. Every helper degrades gracefully to the theme
 * defaults when the plugins are not active, so the theme never fatals.
 *
 * @package cjw-brummen
 */

/**
 * Returns the shared summer camp service, or null when the plugins are
 * not active.
 *
 * @return CJW_Summer_Camp_Service|null
 */
function cjw_brummen_camp()
{
    return function_exists('cjw_summer_camp') ? cjw_summer_camp() : null;
}

/**
 * Converts a hex color to an [r, g, b] array.
 *
 * @param string $hex Hex color (#rgb or #rrggbb).
 * @return array{0: int, 1: int, 2: int}
 */
function cjw_brummen_hex_to_rgb($hex)
{
    $hex = ltrim($hex, '#');

    if (3 === strlen($hex)) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    return [
        (int) hexdec(substr($hex, 0, 2)),
        (int) hexdec(substr($hex, 2, 2)),
        (int) hexdec(substr($hex, 4, 2)),
    ];
}

/**
 * Darkens a hex color by a factor (0–1).
 *
 * @param string $hex Hex color.
 * @param float $factor Multiplier for each channel.
 * @return string Hex color.
 */
function cjw_brummen_shade($hex, $factor)
{
    [ $red, $green, $blue ] = cjw_brummen_hex_to_rgb($hex);

    return sprintf(
        '#%02x%02x%02x',
        (int) round($red * $factor),
        (int) round($green * $factor),
        (int) round($blue * $factor)
    );
}

/**
 * The design palette, with the plugin's theme colors as source of truth.
 *
 * Maps the plugin's primary/secondary/accent onto the design tokens:
 * primary → sage (deep/forest are derived shades), secondary → apricot,
 * accent → the yearly accent.
 *
 * @return array{sage: string, sage_deep: string, forest: string, apricot: string, accent: string}
 */
function cjw_brummen_design_colors()
{
    $defaults = cjw_brummen_front_page_defaults();
    $camp = cjw_brummen_camp();

    if ($camp) {
        $plugin_colors = $camp->getThemeColors();
        $sage = $plugin_colors['primary'];
        $apricot = $plugin_colors['secondary'];
        $accent = $plugin_colors['accent'];
    } else {
        $sage = '#588c7e';
        $apricot = '#f0ae73';
        $accent = $defaults['accent'];
    }

    return [
        'sage' => $sage,
        'sage_deep' => cjw_brummen_shade($sage, 0.72),
        'forest' => cjw_brummen_shade($sage, 0.49),
        'apricot' => $apricot,
        'accent' => $accent,
    ];
}

/**
 * Inline CSS that applies the palette from the plugin settings.
 *
 * Besides the tokens themselves this precomputes the translucent and
 * paper-tinted accent derivatives used as fallbacks where the stylesheet
 * relies on color-mix().
 *
 * @return string
 */
function cjw_brummen_accent_inline_css()
{
    $colors = cjw_brummen_design_colors();
    [ $red, $green, $blue ] = cjw_brummen_hex_to_rgb($colors['accent']);

    // 9% accent blended onto paper (#faf6ee), for the jaarthema band.
    $tint = sprintf(
        '#%02x%02x%02x',
        (int) round($red * 0.09 + 250 * 0.91),
        (int) round($green * 0.09 + 246 * 0.91),
        (int) round($blue * 0.09 + 238 * 0.91)
    );

    return sprintf(
        ':root{--sage:%1$s;--sage-deep:%2$s;--forest:%3$s;--apricot:%4$s;--year-accent:%5$s;--year-accent-soft:rgba(%6$d,%7$d,%8$d,0.4);--year-accent-softer:rgba(%6$d,%7$d,%8$d,0.3);--year-accent-tint:%9$s;}',
        $colors['sage'],
        $colors['sage_deep'],
        $colors['forest'],
        $colors['apricot'],
        $colors['accent'],
        $red,
        $green,
        $blue,
        $tint
    );
}

/**
 * The date sticker text: the camp date range from the plugin.
 *
 * @return string
 */
function cjw_brummen_hero_badge_text()
{
    $camp = cjw_brummen_camp();

    if ($camp) {
        $range = $camp->getDateRangeText();
        if ('' !== $range) {
            return $range;
        }
    }

    return cjw_brummen_front_page_defaults()['hero_badge'];
}

/**
 * The hero title from the plugin settings.
 *
 * @return string
 */
function cjw_brummen_hero_title()
{
    $camp = cjw_brummen_camp();

    if ($camp) {
        $title = $camp->getHeroTitle();
        if ('' !== $title) {
            return $title;
        }
    }

    return cjw_brummen_front_page_defaults()['hero_title'];
}

/**
 * The hero subtitle from the plugin settings.
 *
 * @return string
 */
function cjw_brummen_hero_subtitle()
{
    $camp = cjw_brummen_camp();

    if ($camp) {
        $subtitle = $camp->getHeroSubtitle();
        if ('' !== $subtitle) {
            return $subtitle;
        }
    }

    return cjw_brummen_front_page_defaults()['hero_subtitle'];
}

/**
 * The hero image attachment ID from the plugin settings (0 when unset).
 *
 * @return int
 */
function cjw_brummen_hero_image_id()
{
    $camp = cjw_brummen_camp();

    return $camp ? $camp->getHeroImageId() : 0;
}

/**
 * The yearly theme name (e.g. "Game On!") from the plugin settings.
 *
 * @return string
 */
function cjw_brummen_theme_name()
{
    $camp = cjw_brummen_camp();

    if ($camp) {
        $name = $camp->getThemeName();
        if ('' !== $name) {
            return $name;
        }
    }

    return cjw_brummen_front_page_defaults()['theme_title'];
}

/**
 * The camp year from the plugin settings.
 *
 * @return string
 */
function cjw_brummen_theme_year()
{
    $camp = cjw_brummen_camp();

    if ($camp) {
        $year = $camp->getThemeYear();
        if ('' !== $year) {
            return $year;
        }

        $start = $camp->getStartDate();
        if ($start) {
            return $start->format('Y');
        }
    }

    return substr(cjw_brummen_front_page_defaults()['countdown_date'], 0, 4);
}

/**
 * Whether the camp registration is currently open, per the plugin's
 * registration deadline. True when the plugins are not active.
 *
 * @return bool
 */
function cjw_brummen_registration_open()
{
    $camp = cjw_brummen_camp();

    if ($camp && method_exists($camp, 'isRegistrationOpen')) {
        return $camp->isRegistrationOpen();
    }

    return true;
}

/**
 * The signup call-to-action, from the plugin's primary hero CTA.
 *
 * @return array{label: string, url: string, open: bool}
 */
function cjw_brummen_signup_cta()
{
    $camp = cjw_brummen_camp();

    if ($camp) {
        $cta = $camp->getPrimaryHeroCta();

        if (! empty($cta['url']) && ! empty($cta['label'])) {
            return [
                'label' => $cta['label'],
                'url' => $cta['url'],
                'open' => cjw_brummen_registration_open(),
            ];
        }
    }

    return [
        'label' => __('Schrijf je in!', 'cjw-brummen'),
        'url' => cjw_brummen_front_page_defaults()['signup_url'],
        'open' => cjw_brummen_registration_open(),
    ];
}

/**
 * Reads one plugin setting through the given service getter, falling back
 * to the theme default when the plugins are inactive, the getter does not
 * exist yet, or the stored value is empty.
 *
 * @param string $method Getter method name on CJW_Summer_Camp_Service.
 * @param string $default Theme default from cjw_brummen_front_page_defaults().
 * @return string
 */
function cjw_brummen_camp_setting($method, $default)
{
    $camp = cjw_brummen_camp();

    if ($camp && method_exists($camp, $method)) {
        $value = $camp->{$method}();
        if ('' !== $value) {
            return $value;
        }
    }

    return $default;
}

/**
 * The homepage introduction copy (title + HTML text) from the plugin
 * settings. The title may contain the "|" squiggle marker; render it
 * with cjw_brummen_squiggle_title().
 *
 * @return array{title: string, text: string}
 */
function cjw_brummen_home_intro()
{
    $defaults = cjw_brummen_front_page_defaults();

    return [
        'title' => cjw_brummen_camp_setting('getHomeIntroTitle', $defaults['home_intro_title']),
        'text' => cjw_brummen_camp_setting('getHomeIntroText', $defaults['home_intro_text']),
    ];
}

/**
 * The copy for the three front page cards from the plugin settings. The
 * {jaar} token in the signup text is already replaced with the camp year.
 *
 * @return array{zomerkamp: array{title: string, text: string}, info: array{title: string, text: string}, signup: array{title: string, text: string}}
 */
function cjw_brummen_cards_copy()
{
    $defaults = cjw_brummen_front_page_defaults();
    $signup_text = cjw_brummen_camp_setting('getCardSignupText', $defaults['card_signup_text']);

    return [
        'zomerkamp' => [
            'title' => cjw_brummen_camp_setting('getCardZomerkampTitle', $defaults['card_zomerkamp_title']),
            'text' => cjw_brummen_camp_setting('getCardZomerkampText', $defaults['card_zomerkamp_text']),
        ],
        'info' => [
            'title' => cjw_brummen_camp_setting('getCardInfoTitle', $defaults['card_info_title']),
            'text' => cjw_brummen_camp_setting('getCardInfoText', $defaults['card_info_text']),
        ],
        'signup' => [
            'title' => cjw_brummen_camp_setting('getCardSignupTitle', $defaults['card_signup_title']),
            'text' => str_replace('{jaar}', cjw_brummen_theme_year(), $signup_text),
        ],
    ];
}

/**
 * The photo-wall copy (title + lead) from the plugin settings. The title
 * may contain the "|" squiggle marker.
 *
 * @return array{title: string, lead: string}
 */
function cjw_brummen_photos_copy()
{
    $defaults = cjw_brummen_front_page_defaults();

    return [
        'title' => cjw_brummen_camp_setting('getPhotosTitle', $defaults['photos_title']),
        'lead' => cjw_brummen_camp_setting('getPhotosLead', $defaults['photos_lead']),
    ];
}

/**
 * The sponsor section copy (title, lead and the "become a sponsor" CTA)
 * from the plugin settings. The title may contain the "|" squiggle marker.
 *
 * @return array{title: string, lead: string, cta: string}
 */
function cjw_brummen_sponsors_copy()
{
    $defaults = cjw_brummen_front_page_defaults();

    return [
        'title' => cjw_brummen_camp_setting('getSponsorsTitle', $defaults['sponsors_title']),
        'lead' => cjw_brummen_camp_setting('getSponsorsLead', $defaults['sponsors_lead']),
        'cta' => cjw_brummen_camp_setting('getSponsorsCtaText', $defaults['sponsors_cta_text']),
    ];
}

/**
 * The footer copy (about line + organisation line) from the plugin
 * settings.
 *
 * @return array{about: string, org: string}
 */
function cjw_brummen_footer_copy()
{
    $defaults = cjw_brummen_front_page_defaults();

    return [
        'about' => cjw_brummen_camp_setting('getFooterAbout', $defaults['footer_about']),
        'org' => cjw_brummen_camp_setting('getFooterOrg', $defaults['footer_org']),
    ];
}

/**
 * The signup CTA band copy (title + text) from the plugin settings.
 *
 * @return array{title: string, text: string}
 */
function cjw_brummen_cta_band()
{
    $defaults = cjw_brummen_front_page_defaults();

    return [
        'title' => cjw_brummen_camp_setting('getCtaTitle', $defaults['cta_title']),
        'text' => cjw_brummen_camp_setting('getCtaText', $defaults['cta_text']),
    ];
}

/**
 * The camp fact lines (age range + location) from the plugin settings.
 *
 * @return array{age: string, location: string}
 */
function cjw_brummen_camp_facts()
{
    $defaults = cjw_brummen_front_page_defaults();

    return [
        'age' => cjw_brummen_camp_setting('getCampAgeRange', $defaults['camp_age_range']),
        'location' => cjw_brummen_camp_setting('getCampLocation', $defaults['camp_location']),
    ];
}

/**
 * Renders a title with the "|" squiggle marker: the part after the first
 * "|" is wrapped in <span class="squiggle">. Both halves are escaped with
 * esc_html(), so the returned string is safe HTML ready to echo.
 *
 * @param string $title Title, e.g. "Welkom bij |CJW Zomerkampen".
 * @return string Escaped HTML.
 */
function cjw_brummen_squiggle_title($title)
{
    $parts = explode('|', $title, 2);

    if (2 === count($parts)) {
        return esc_html($parts[0]) . '<span class="squiggle">' . esc_html($parts[1]) . '</span>';
    }

    return esc_html($title);
}

/**
 * The yearly theme description (HTML) from the plugin settings.
 *
 * @return string
 */
function cjw_brummen_theme_description()
{
    $camp = cjw_brummen_camp();

    if ($camp && method_exists($camp, 'getThemeDescription')) {
        $description = $camp->getThemeDescription();
        if ('' !== $description) {
            return $description;
        }
    }

    return cjw_brummen_front_page_defaults()['theme_text'];
}

/**
 * The attachment ID of the homepage introduction photo (0 when unset).
 *
 * @return int
 */
function cjw_brummen_intro_image_id()
{
    $camp = cjw_brummen_camp();

    return ($camp && method_exists($camp, 'getIntroImageId')) ? $camp->getIntroImageId() : 0;
}

/**
 * Permalink for one of the front page cards, from the plugin settings.
 *
 * @param string $card Card key: 'zomerkamp' or 'info'.
 * @return string
 */
function cjw_brummen_card_link($card)
{
    $fallback = 'info' === $card ? '#praktische-info' : '#zomerkamp';
    $camp = cjw_brummen_camp();

    if (! $camp) {
        return $fallback;
    }

    if ('info' === $card) {
        $page_id = method_exists($camp, 'getCardInfoPageId') ? $camp->getCardInfoPageId() : 0;
    } else {
        $page_id = method_exists($camp, 'getCardZomerkampPageId') ? $camp->getCardZomerkampPageId() : 0;
    }

    if ($page_id) {
        $permalink = get_permalink($page_id);
        if ($permalink) {
            return $permalink;
        }
    }

    return $fallback;
}

/**
 * The photo-wall polaroids (slot => image_id + caption) from the plugin
 * settings. Only slots with an image are returned.
 *
 * @return array<int, array{image_id: int, caption: string}>
 */
function cjw_brummen_polaroids()
{
    $camp = cjw_brummen_camp();

    return ($camp && method_exists($camp, 'getPolaroids')) ? $camp->getPolaroids() : [];
}

/**
 * The public contact e-mail address from the plugin settings.
 *
 * @return string
 */
function cjw_brummen_contact_email()
{
    $camp = cjw_brummen_camp();

    if ($camp && method_exists($camp, 'getContactEmail')) {
        $email = $camp->getContactEmail();
        if ('' !== $email) {
            return $email;
        }
    }

    return cjw_brummen_front_page_defaults()['contact_email'];
}

/**
 * The social profile URLs from the plugin settings.
 *
 * @return array{facebook: string, instagram: string}
 */
function cjw_brummen_social_links()
{
    $camp = cjw_brummen_camp();

    if ($camp && method_exists($camp, 'getSocialLinks')) {
        return $camp->getSocialLinks();
    }

    return [
        'facebook' => '',
        'instagram' => '',
    ];
}

/**
 * Published sponsors from the ACF "Sponsor" post type (source of truth).
 *
 * Only sponsors with a logo (featured image) are returned; the link comes
 * from the ACF sponsor_link field.
 *
 * @return array<int, array{id: int, title: string, url: string, logo_id: int}>
 */
function cjw_brummen_sponsors()
{
    if (! post_type_exists('sponsor')) {
        return [];
    }

    $query = new WP_Query(
        [
            'post_type' => 'sponsor',
            'post_status' => 'publish',
            'posts_per_page' => 20,
            'orderby' => [
                'menu_order' => 'ASC',
                'title' => 'ASC',
            ],
            'no_found_rows' => true,
            'update_post_term_cache' => false,
        ]
    );

    $sponsors = [];

    foreach ($query->posts as $cjw_brummen_sponsor_post) {
        $logo_id = (int) get_post_thumbnail_id($cjw_brummen_sponsor_post);

        if ($logo_id <= 0) {
            continue;
        }

        $url = function_exists('get_field')
            ? get_field('sponsor_link', $cjw_brummen_sponsor_post->ID)
            : get_post_meta($cjw_brummen_sponsor_post->ID, 'sponsor_link', true);

        $sponsors[] = [
            'id' => $cjw_brummen_sponsor_post->ID,
            'title' => get_the_title($cjw_brummen_sponsor_post),
            'url' => is_string($url) ? esc_url_raw($url) : '',
            'logo_id' => $logo_id,
        ];
    }

    return $sponsors;
}

/**
 * Current camp prices from the plugin (early-bird aware), or null when
 * unavailable or not configured.
 *
 * @return array{week: int, weekend: int}|null
 */
function cjw_brummen_camp_prices()
{
    $camp = cjw_brummen_camp();

    if (! $camp || ! enum_exists('CampType')) {
        return null;
    }

    $prices = [
        'week' => $camp->getPrice(CampType::WEEK),
        'weekend' => $camp->getPrice(CampType::WEEKEND),
    ];

    return ($prices['week'] > 0 || $prices['weekend'] > 0) ? $prices : null;
}

/**
 * The countdown for the yearly theme block, driven by the plugin's camp
 * start/end dates.
 *
 * Before camp: nights until the first day (09:00). During camp (through
 * the end date): "NU". Afterwards: next year's number.
 *
 * @return array{value: string, label: string, timestamp: int, now_until: int, done_value: string}
 */
function cjw_brummen_countdown()
{
    $defaults = cjw_brummen_front_page_defaults();
    $timezone = wp_timezone();
    $camp = cjw_brummen_camp();

    $start_date = $defaults['countdown_date'];
    $end_date = '';

    if ($camp) {
        $start = $camp->getStartDate();
        if ($start) {
            $start_date = $start->format('Y-m-d');
        }

        $end = $camp->getEndDate();
        if ($end) {
            $end_date = $end->format('Y-m-d');
        }
    }

    $target = date_create_immutable($start_date . ' 09:00:00', $timezone);

    // The "NU" period runs through the end date; without one, use the
    // week after the start (the original design behavior).
    $now_until = '' !== $end_date
        ? date_create_immutable($end_date . ' 00:00:00', $timezone)->modify('+1 day')
        : $target->modify('+8 days');

    $done_value = (string) ((int) cjw_brummen_theme_year() + 1);
    $now = time();

    if ($now < $target->getTimestamp()) {
        $days = (int) ceil(($target->getTimestamp() - $now) / DAY_IN_SECONDS);
        $value = (string) $days;
        $label = _n('nachtje slapen tot kamp!', 'nachtjes slapen tot kamp!', $days, 'cjw-brummen');
    } elseif ($now < $now_until->getTimestamp()) {
        $value = __('NU', 'cjw-brummen');
        $label = __('We zitten nú op kamp in het bos!', 'cjw-brummen');
    } else {
        $value = $done_value;
        $label = __('Dat was ’m weer — tot volgend jaar!', 'cjw-brummen');
    }

    return [
        'value' => $value,
        'label' => $label,
        'timestamp' => $target->getTimestamp(),
        'now_until' => $now_until->getTimestamp(),
        'done_value' => $done_value,
    ];
}
