<?php

/**
 * The cjw/* block definitions.
 *
 * Every block is a server-rendered wrapper around a template part, named by a
 * `part` string. A typo there is not an error anybody sees: get_template_part()
 * fails silently, the block renders nothing, and in the editor that looks
 * exactly like a section with no content yet. So the parts are checked against
 * the filesystem here instead.
 *
 * @package cjw-brummen
 */

declare(strict_types=1);

final class BlockDefinitionsTest extends CJW_Brummen_TestCase
{
    /**
     * @return array<string, array<string, mixed>>
     */
    private function definitions(): array
    {
        return cjw_brummen_block_definitions();
    }

    public function test_every_block_names_a_template_part_that_exists(): void
    {
        foreach ($this->definitions() as $slug => $definition) {
            $path = dirname(__DIR__) . '/template-parts/' . $definition['part'] . '.php';

            $this->assertFileExists(
                $path,
                "Block cjw/{$slug} renders template-parts/{$definition['part']}.php, which is not there."
                . ' get_template_part() fails silently, so this would ship as a block that renders nothing.'
            );
        }
    }

    public function test_the_front_page_blocks_keep_their_slug_as_their_part(): void
    {
        foreach (['hero', 'intro', 'jaarthema', 'cards', 'photos', 'sponsors'] as $slug) {
            $this->assertSame(
                'front-page/' . $slug,
                $this->definitions()[$slug]['part'],
                "The default that fills in `part` for the front page blocks no longer covers {$slug}."
            );
        }
    }

    public function test_the_verhuur_blocks_render_the_verhuur_parts(): void
    {
        $expected = [
            'verhuur-past-het' => 'verhuur/fit',
            'verhuur-kaarten' => 'verhuur/cards',
            'verhuur-tabel' => 'verhuur/table',
        ];

        foreach ($expected as $slug => $part) {
            $this->assertSame($part, $this->definitions()[$slug]['part']);
        }
    }

    public function test_every_verhuur_block_offers_an_editable_heading(): void
    {
        foreach (['verhuur-past-het', 'verhuur-kaarten', 'verhuur-tabel'] as $slug) {
            $attributes = $this->definitions()[$slug]['attributes'];

            $this->assertArrayHasKey('titel', $attributes, "cjw/{$slug} has no heading to edit.");
            $this->assertSame('string', $attributes['titel']['type']);
            $this->assertNotSame(
                '',
                $attributes['titel']['default'],
                "cjw/{$slug} would insert with no heading at all, which reads as a broken block."
            );
        }
    }

    public function test_the_front_page_blocks_have_no_attributes(): void
    {
        // They render whatever the camp settings say; there is nothing on the
        // block itself to set, and an empty schema is what register_block_type
        // expects for that.
        foreach (['hero', 'intro', 'jaarthema', 'cards', 'photos', 'sponsors'] as $slug) {
            $this->assertSame([], $this->definitions()[$slug]['attributes']);
        }
    }

    public function test_every_block_carries_the_metadata_the_inserter_shows(): void
    {
        foreach ($this->definitions() as $slug => $definition) {
            foreach (['title', 'description', 'icon', 'keywords'] as $key) {
                $this->assertArrayHasKey($key, $definition, "cjw/{$slug} is missing '{$key}'.");
                $this->assertNotEmpty($definition[$key], "cjw/{$slug} has an empty '{$key}'.");
            }
        }
    }

    public function test_the_editor_placeholder_names_the_block_it_stands_in_for(): void
    {
        foreach ($this->definitions() as $slug => $definition) {
            $placeholder = cjw_brummen_block_placeholder($slug);

            // Escaped, because the placeholder is HTML: "CJW Jaarthema &
            // aftellen" reaches the editor as "&amp;".
            $this->assertStringContainsString(
                esc_html($definition['title']),
                $placeholder,
                "The empty-state hint for cjw/{$slug} does not say which section it belongs to."
            );
        }
    }
}
