<?php

/**
 * Dynamic blocks for the front page and verhuur sections.
 *
 * Each block is a thin server-rendered wrapper around a template part, so the
 * markup stays identical to the classic template while the section becomes
 * something an editor can insert, reorder and remove. The editor preview lives
 * in js/blocks.js (ServerSideRender).
 *
 * The verhuur blocks exist because the verhuurpagina was a template that
 * decided everything: the three sections always appeared, always in the same
 * order, always under the same headings, and the page's own copy could only go
 * above all of them. As blocks they are inserted with "/", moved, left out, and
 * written between -- and each carries its heading as an attribute, so the
 * section title is the editor's too.
 *
 * @package cjw-brummen
 */

/**
 * Every cjw/* block: slug => editor metadata.
 *
 * `part` is the template part the block renders, and `attributes` is the block
 * attribute schema WordPress validates against and hands to the render
 * callback. A block with no attributes declares none.
 *
 * @return array<string, array{title: string, description: string, icon: string, keywords: array<int, string>, part: string, attributes: array<string, array<string, mixed>>}>
 */
function cjw_brummen_block_definitions()
{
    $blocks = [
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
        'verhuur-past-het' => [
            'title' => __('CJW Past het?', 'cjw-brummen'),
            'description' => __('De rekenhulp die een groepsgrootte omzet in een combinatie van groepstenten.', 'cjw-brummen'),
            'icon' => 'calculator',
            'keywords' => [
                __('verhuur', 'cjw-brummen'),
                __('tenten', 'cjw-brummen'),
                __('personen', 'cjw-brummen'),
            ],
            'part' => 'verhuur/fit',
            'attributes' => [
                'titel' => [
                    'type' => 'string',
                    'default' => 'Past het?',
                ],
            ],
        ],
        'verhuur-kaarten' => [
            'title' => __('CJW Verhuurkaarten', 'cjw-brummen'),
            'description' => __('Een kaart per tent, met de plattegrond op schaal.', 'cjw-brummen'),
            'icon' => 'grid-view',
            'keywords' => [
                __('verhuur', 'cjw-brummen'),
                __('tenten', 'cjw-brummen'),
                __('plattegrond', 'cjw-brummen'),
            ],
            'part' => 'verhuur/cards',
            'attributes' => [
                'titel' => [
                    'type' => 'string',
                    'default' => 'Wat verhuren we zoal?',
                ],
            ],
        ],
        'verhuur-tabel' => [
            'title' => __('CJW Verhuurtabel', 'cjw-brummen'),
            'description' => __('Het hele verhuuraanbod in één tabel, met de totalen eronder.', 'cjw-brummen'),
            'icon' => 'editor-table',
            'keywords' => [
                __('verhuur', 'cjw-brummen'),
                __('tabel', 'cjw-brummen'),
                __('materiaal', 'cjw-brummen'),
            ],
            'part' => 'verhuur/table',
            'attributes' => [
                'titel' => [
                    'type' => 'string',
                    'default' => 'Alles op een rij',
                ],
            ],
        ],
    ];

    // The front page blocks predate `part`: their slug has always doubled as
    // the template part name, and spelling that out nine times would only
    // invite one of them to drift from its file.
    foreach ($blocks as $slug => $definition) {
        if (! isset($definition['part'])) {
            $blocks[$slug]['part'] = 'front-page/' . $slug;
        }

        if (! isset($definition['attributes'])) {
            $blocks[$slug]['attributes'] = [];
        }
    }

    return $blocks;
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
        'verhuur-past-het' => __('Voeg groepstenten toe via Verhuurmateriaal in het menu. Een tent telt hier mee zodra hij een aantal én een capaciteit heeft.', 'cjw-brummen'),
        'verhuur-kaarten' => __('Voeg tenten toe via Verhuurmateriaal in het menu. Vul lengte en breedte in om de plattegrond te laten tekenen.', 'cjw-brummen'),
        'verhuur-tabel' => __('Voeg materiaal toe via Verhuurmateriaal in het menu.', 'cjw-brummen'),
    ];
    $hint = $hints[$slug] ?? __('Vul deze sectie met inhoud via Zomerkamp → Website.', 'cjw-brummen');

    return '<div class="cjw-block-placeholder"><strong>' . esc_html($title) . '</strong><span>'
        . esc_html($hint) . '</span></div>';
}

/**
 * Creates the render callback for a block: it renders the matching template
 * part, so the markup stays identical to the classic template.
 *
 * The block's attributes are passed through to the part as $args. The parts
 * default every value they read, so a part still renders correctly when it is
 * included directly by a template with no block around it.
 *
 * @param string $slug Block slug.
 * @param string $part Template part under template-parts/.
 * @return callable(array<string, mixed>): string
 */
function cjw_brummen_block_renderer($slug, $part)
{
    return function ($attributes = []) use ($slug, $part) {
        ob_start();
        get_template_part('template-parts/' . $part, null, is_array($attributes) ? $attributes : []);
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
 * Registers the editor script and the cjw/* dynamic blocks.
 */
function cjw_brummen_register_blocks(): void
{
    wp_register_script(
        'cjw-brummen-blocks',
        get_template_directory_uri() . '/js/blocks.js',
        // wp-components and wp-i18n are for the verhuur blocks' heading field
        // in the block sidebar; the front page blocks have no settings.
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-server-side-render', 'wp-components', 'wp-i18n'],
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
                'attributes' => $definition['attributes'],
                'supports' => [
                    'html' => false,
                    'multiple' => false,
                    'reusable' => false,
                ],
                // editor_script (singular) has been deprecated since
                // WordPress 6.1 in favour of the *_handles array form.
                'editor_script_handles' => [ 'cjw-brummen-blocks' ],
                'render_callback' => cjw_brummen_block_renderer($slug, $definition['part']),
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
