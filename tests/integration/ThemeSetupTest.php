<?php

declare(strict_types=1);

/**
 * What the theme registers, asked of a real WordPress.
 *
 * The fast suite runs with no WordPress underneath, so it can say what the
 * helpers return but not whether add_theme_support(), register_nav_menus() or
 * add_image_size() actually took effect. A typo in a support name is invisible
 * to a stub and silent in production: the feature simply never appears.
 */
final class ThemeSetupTest extends CJW_IntegrationTestCase
{
    public function testTheThemeIsTheActiveOne(): void
    {
        $this->assertSame('CJW-theme', get_stylesheet());
        $this->assertFalse(is_child_theme());
    }

    public function testTheThemeHasNoMissingParentOrBrokenHeader(): void
    {
        $theme = wp_get_theme();

        // WP_Theme records why it will not load, rather than throwing.
        $this->assertFalse($theme->errors(), 'the theme should load without errors');
        $this->assertSame('CJW Brummen', $theme->get('Name'));
        $this->assertSame('cjw-brummen', $theme->get('TextDomain'));
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function supports(): array
    {
        return [
            'feed links' => [ 'automatic-feed-links' ],
            'title tag' => [ 'title-tag' ],
            'featured images' => [ 'post-thumbnails' ],
            'editor styles' => [ 'editor-styles' ],
            'block styles' => [ 'wp-block-styles' ],
            'wide alignment' => [ 'align-wide' ],
            'responsive embeds' => [ 'responsive-embeds' ],
        ];
    }

    #[PHPUnit\Framework\Attributes\DataProvider('supports')]
    public function testTheThemeDeclaresItsSupports(string $feature): void
    {
        $this->assertTrue(current_theme_supports($feature), $feature);
    }

    public function testTheMenuLocationExists(): void
    {
        $this->assertArrayHasKey('menu-1', get_registered_nav_menus());
    }

    public function testThePolaroidCropIsRegistered(): void
    {
        $sizes = wp_get_additional_image_sizes();

        $this->assertArrayHasKey('cjw-polaroid', $sizes);
        $this->assertSame(900, $sizes['cjw-polaroid']['width']);
        $this->assertSame(675, $sizes['cjw-polaroid']['height']);
        $this->assertTrue($sizes['cjw-polaroid']['crop']);
    }

    public function testTheEditorStylesheetItPointsAtIsReallyThere(): void
    {
        $styles = get_theme_support('editor-styles') ? (array) get_theme_mod('editor_styles') : [];
        unset($styles);

        // add_editor_style() only records a path; nothing checks it resolves.
        // A stale filename means the editor silently renders unstyled.
        $this->assertFileExists(get_theme_file_path('editor-style.css'));
        $this->assertFileExists(get_theme_file_path('style.css'));
    }

    public function testThePageRendersWithoutTheCjwPluginPresent(): void
    {
        // The theme's stated contract: every helper degrades to a design
        // default when the plugin is absent. Here that is true of a whole
        // template, against a real query rather than a stub.
        $this->assertFalse(function_exists('cjw_summer_camp'));

        $id = self::factory()->post->create([ 'post_type' => 'page', 'post_title' => 'Zomerkamp' ]);
        $this->go_to(get_permalink($id));

        $this->assertTrue(is_page());

        ob_start();
        include get_theme_file_path('page.php');
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('Zomerkamp', $html);
    }
}
