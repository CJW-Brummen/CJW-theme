<?php

/**
 * Dynamic blocks for the front page sections.
 *
 * The front page is a static page composed of six cjw/* blocks. Each block
 * is a thin server-rendered wrapper around the matching template part in
 * template-parts/front-page/, so the homepage markup stays identical to the
 * classic template while becoming editable (reorder/remove) in the editor.
 * The editor preview lives in js/blocks.js (ServerSideRender).
 *
 * @package cjw-brummen
 */

/**
 * The six front page blocks: slug => editor metadata.
 *
 * The slug doubles as the template part name in template-parts/front-page/.
 *
 * @return array<string, array{title: string, description: string, icon: string, keywords: array<int, string>}>
 */
function cjw_brummen_block_definitions()
{
    return [
        'hero' => [
            'title' => __('CJW Hero', 'cjw-brummen'),
            'description' => __('De openingssectie met kampfoto, datumbadge en aanmeldknop.', 'cjw-brummen'),
            'icon' => 'cover-image',
            'keywords' => [
                __('hero', 'cjw-brummen'),
                __('kamp', 'cjw-brummen'),
                __('aanmelden', 'cjw-brummen'),
            ],
        ],
        'intro' => [
            'title' => __('CJW Introductie', 'cjw-brummen'),
            'description' => __('De welkomsttekst met de belangrijkste kampfeiten.', 'cjw-brummen'),
            'icon' => 'text-page',
            'keywords' => [
                __('introductie', 'cjw-brummen'),
                __('welkom', 'cjw-brummen'),
                __('over', 'cjw-brummen'),
            ],
        ],
        'jaarthema' => [
            'title' => __('CJW Jaarthema & aftellen', 'cjw-brummen'),
            'description' => __('De jaarthema-teaser met het aftellen naar het kamp.', 'cjw-brummen'),
            'icon' => 'clock',
            'keywords' => [
                __('jaarthema', 'cjw-brummen'),
                __('aftellen', 'cjw-brummen'),
                __('countdown', 'cjw-brummen'),
            ],
        ],
        'cards' => [
            'title' => __('CJW Snel-naar kaarten', 'cjw-brummen'),
            'description' => __('De drie navigatiekaarten naar zomerkamp, praktische info en inschrijven.', 'cjw-brummen'),
            'icon' => 'grid-view',
            'keywords' => [
                __('kaarten', 'cjw-brummen'),
                __('navigatie', 'cjw-brummen'),
                __('inschrijven', 'cjw-brummen'),
            ],
        ],
        'photos' => [
            'title' => __('CJW Kampplakboek', 'cjw-brummen'),
            'description' => __('De polaroid-fotowand met beelden van vorige kampen.', 'cjw-brummen'),
            'icon' => 'format-gallery',
            'keywords' => [
                __('foto\'s', 'cjw-brummen'),
                __('plakboek', 'cjw-brummen'),
                __('polaroid', 'cjw-brummen'),
            ],
        ],
        'sponsors' => [
            'title' => __('CJW Sponsors', 'cjw-brummen'),
            'description' => __('De sponsorsectie met logo\'s en de sponsor-oproep.', 'cjw-brummen'),
            'icon' => 'awards',
            'keywords' => [
                __('sponsors', 'cjw-brummen'),
                __('sponsoren', 'cjw-brummen'),
                __('partners', 'cjw-brummen'),
            ],
        ],
    ];
}

/**
 * Editor-only placeholder for sections that render nothing yet, so an empty
 * block is not an invisible block. Sections hide themselves on the front end
 * when their content is missing, which would otherwise leave the editor
 * showing a blank preview with no hint about what to do.
 *
 * @param string $slug Block slug.
 * @return string
 */
function cjw_brummen_block_placeholder($slug)
{
    $definitions = cjw_brummen_block_definitions();
    $title = $definitions[$slug]['title'] ?? $slug;

    $hints = [
        'photos' => __('Kies foto\'s bij Zomerkamp → Website → Kampplakboek. Zonder foto\'s blijft deze sectie verborgen op de site.', 'cjw-brummen'),
        'sponsors' => __('Voeg sponsoren toe via Sponsoren in het menu. Een sponsor heeft een logo (uitgelichte afbeelding) nodig.', 'cjw-brummen'),
    ];
    $hint = $hints[$slug] ?? __('Vul deze sectie met inhoud via Zomerkamp → Website.', 'cjw-brummen');

    return '<div class="cjw-block-placeholder"><strong>' . esc_html($title) . '</strong><span>'
        . esc_html($hint) . '</span></div>';
}

/**
 * Creates the render callback for a front page block: it renders the
 * matching template part, so the markup stays identical to the classic
 * front page template.
 *
 * @param string $slug Block slug, also the template part name.
 * @return callable(): string
 */
function cjw_brummen_block_renderer($slug)
{
    return function () use ($slug) {
        ob_start();
        get_template_part('template-parts/front-page/' . $slug);
        $output = ob_get_clean();

        // In the editor an empty section would be an invisible block; show
        // what to do instead. The front end keeps the section hidden.
        if ('' === trim((string) $output) && cjw_brummen_is_editor_request()) {
            return cjw_brummen_block_placeholder($slug);
        }

        return $output;
    };
}

/**
 * Whether the current request renders a block for the editor (the block
 * renderer REST route) rather than for a visitor.
 *
 * @return bool
 */
function cjw_brummen_is_editor_request()
{
    // A REST request is not by itself the editor -- the REST API is public, and
    // any unauthenticated caller hitting the block-renderer route counted as
    // "in the editor" and got the back-office instruction text back. Editing
    // rights are what actually distinguish the two.
    return defined('REST_REQUEST') && REST_REQUEST && current_user_can('edit_posts');
}

/**
 * Registers the editor script and the six cjw/* dynamic blocks.
 */
function cjw_brummen_register_blocks(): void
{
    wp_register_script(
        'cjw-brummen-blocks',
        get_template_directory_uri() . '/js/blocks.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-server-side-render'],
        CJW_BRUMMEN_VERSION,
        false
    );

    foreach (cjw_brummen_block_definitions() as $slug => $definition) {
        register_block_type(
            'cjw/' . $slug,
            [
                'api_version' => 3,
                'title' => $definition['title'],
                'description' => $definition['description'],
                'category' => 'cjw',
                'icon' => $definition['icon'],
                'keywords' => $definition['keywords'],
                'supports' => [
                    'html' => false,
                    'multiple' => false,
                    'reusable' => false,
                ],
                // editor_script (singular) has been deprecated since
                // WordPress 6.1 in favour of the *_handles array form.
                'editor_script_handles' => [ 'cjw-brummen-blocks' ],
                'render_callback' => cjw_brummen_block_renderer($slug),
            ]
        );
    }
}

/**
 * Adds the "CJW Zomerkamp" block category at the front of the list.
 *
 * @param array<int, array<string, mixed>> $categories Registered block categories.
 * @return array<int, array<string, mixed>>
 */
function cjw_brummen_block_categories($categories)
{
    array_unshift(
        $categories,
        [
            'slug' => 'cjw',
            'title' => __('CJW Zomerkamp', 'cjw-brummen'),
        ]
    );

    return $categories;
}

add_action('init', 'cjw_brummen_register_blocks');
add_filter('block_categories_all', 'cjw_brummen_block_categories');
