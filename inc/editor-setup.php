<?php

/**
 * Block editor integration for the CJW design.
 *
 * Registers the editor stylesheet and block-editor theme supports, keeps
 * the theme.json palette in sync with the runtime plugin colors, injects
 * the design tokens and local webfonts into the editor iframe and
 * registers the theme's block style and pattern category.
 *
 * @package cjw-brummen
 */

/**
 * Theme supports for the block editor.
 */
function cjw_brummen_editor_setup(): void
{
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
}

/**
 * Keeps the theme.json color palette in sync with the live design palette.
 *
 * The cjw-summer-camp plugin settings are the source of truth for the
 * colors (see cjw_brummen_design_colors() in inc/summer-camp.php); this
 * updates the accent presets so the editor swatches always match the
 * front end. The full palette is passed because presets are replaced as
 * a whole when merging theme.json data.
 *
 * @param WP_Theme_JSON_Data $theme_json The theme.json data object.
 * @return WP_Theme_JSON_Data
 */
function cjw_brummen_editor_theme_json($theme_json)
{
    if (! function_exists('cjw_brummen_design_colors')) {
        return $theme_json;
    }

    $colors = cjw_brummen_design_colors();

    return $theme_json->update_with(
        [
            'version' => 3,
            'settings' => [
                'color' => [
                    'palette' => [
                        [
                            'slug' => 'papier',
                            'color' => '#faf6ee',
                            'name' => __('Papier', 'cjw-brummen'),
                        ],
                        [
                            'slug' => 'inkt',
                            'color' => '#26332d',
                            'name' => __('Inkt', 'cjw-brummen'),
                        ],
                        [
                            'slug' => 'salie',
                            'color' => $colors['sage'],
                            'name' => __('Salie', 'cjw-brummen'),
                        ],
                        [
                            'slug' => 'salie-diep',
                            'color' => $colors['sage_deep'],
                            'name' => __('Salie donker', 'cjw-brummen'),
                        ],
                        [
                            'slug' => 'bos',
                            'color' => $colors['forest'],
                            'name' => __('Bos', 'cjw-brummen'),
                        ],
                        [
                            'slug' => 'abrikoos',
                            'color' => $colors['apricot'],
                            'name' => __('Abrikoos', 'cjw-brummen'),
                        ],
                        [
                            'slug' => 'jaaraccent',
                            'color' => $colors['accent'],
                            'name' => __('Jaaraccent', 'cjw-brummen'),
                        ],
                    ],
                ],
            ],
        ]
    );
}

/**
 * The @font-face rules for the local webfonts, with absolute URLs.
 *
 * Mirrors sass/cjw/_fonts.scss. The editor rebases relative url()s in
 * editor styles against the stylesheet, but absolute URLs guarantee the
 * fonts load inside the editor iframe regardless of that rebasing.
 *
 * @return string
 */
function cjw_brummen_editor_fonts_css()
{
    $fonts_url = get_template_directory_uri() . '/assets/fonts/';

    $latin = 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD';
    $latin_ext = 'U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF';

    $faces = [
        ['Amatic SC', '400', 'amatic-sc-400-latin.woff2', $latin],
        ['Amatic SC', '400', 'amatic-sc-400-latin-ext.woff2', $latin_ext],
        ['Amatic SC', '700', 'amatic-sc-700-latin.woff2', $latin],
        ['Amatic SC', '700', 'amatic-sc-700-latin-ext.woff2', $latin_ext],
        ['Nunito', '400 900', 'nunito-var-latin.woff2', $latin],
        ['Nunito', '400 900', 'nunito-var-latin-ext.woff2', $latin_ext],
    ];

    $css = '';

    foreach ($faces as [ $family, $weight, $file, $range ]) {
        $css .= sprintf(
            '@font-face{font-family:"%1$s";font-style:normal;font-weight:%2$s;font-display:swap;src:url("%3$s") format("woff2");unicode-range:%4$s;}',
            $family,
            $weight,
            esc_url($fonts_url . $file),
            $range
        );
    }

    return $css;
}

/**
 * Injects the runtime design tokens and webfonts into the editor iframe.
 *
 * The :root custom properties from the plugin palette normally only exist
 * on the front end (wp_add_inline_style in functions.php); appending them
 * to the editor styles keeps the canvas WYSIWYG.
 *
 * @param array<string, mixed> $settings The block editor settings.
 * @return array<string, mixed>
 */
function cjw_brummen_editor_settings($settings)
{
    if (! isset($settings['styles']) || ! is_array($settings['styles'])) {
        $settings['styles'] = [];
    }

    if (function_exists('cjw_brummen_accent_inline_css')) {
        $settings['styles'][] = ['css' => cjw_brummen_accent_inline_css()];
    }

    $settings['styles'][] = ['css' => cjw_brummen_editor_fonts_css()];

    return $settings;
}

/**
 * Registers the block style and the block pattern category.
 *
 * Hooked before init 10 so the category exists when WordPress registers
 * the patterns/ directory.
 */
function cjw_brummen_editor_blocks_init(): void
{
    register_block_style(
        'core/list',
        [
            'name' => 'geen-vinkjes',
            'label' => __('Zonder vinkjes', 'cjw-brummen'),
        ]
    );

    register_block_style(
        'core/image',
        [
            'name' => 'kiekje',
            'label' => __('Kiekje', 'cjw-brummen'),
        ]
    );

    register_block_style(
        'core/gallery',
        [
            'name' => 'fotostrook',
            'label' => __('Fotostrook', 'cjw-brummen'),
        ]
    );

    register_block_pattern_category(
        'cjw-brummen',
        [
            'label' => __('CJW Zomerkamp', 'cjw-brummen'),
        ]
    );
}

add_action('after_setup_theme', 'cjw_brummen_editor_setup');
add_filter('wp_theme_json_data_theme', 'cjw_brummen_editor_theme_json');
add_filter('block_editor_settings_all', 'cjw_brummen_editor_settings');
add_action('init', 'cjw_brummen_editor_blocks_init', 9);
