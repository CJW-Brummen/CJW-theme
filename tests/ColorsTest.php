<?php

declare(strict_types=1);

final class ColorsTest extends CJW_Brummen_TestCase
{
    public function testConvertsSixDigitHex(): void
    {
        $this->assertSame([ 88, 140, 126 ], cjw_brummen_hex_to_rgb('#588c7e'));
    }

    public function testExpandsThreeDigitHex(): void
    {
        $this->assertSame([ 170, 187, 204 ], cjw_brummen_hex_to_rgb('#abc'));
    }

    public function testAcceptsHexWithoutHash(): void
    {
        $this->assertSame([ 255, 255, 255 ], cjw_brummen_hex_to_rgb('ffffff'));
    }

    public function testShadeOfOneIsTheIdentity(): void
    {
        $this->assertSame('#588c7e', cjw_brummen_shade('#588c7e', 1.0));
    }

    public function testShadeHalvesEveryChannel(): void
    {
        $this->assertSame('#808080', cjw_brummen_shade('#ffffff', 0.5));
    }

    public function testPaletteFallsBackToTheDesignDefaults(): void
    {
        $colors = cjw_brummen_design_colors();

        $this->assertSame(
            [ 'sage', 'sage_deep', 'forest', 'apricot', 'accent' ],
            array_keys($colors)
        );
        $this->assertSame('#588c7e', $colors['sage']);
        $this->assertSame('#f0ae73', $colors['apricot']);
        $this->assertSame(cjw_brummen_front_page_defaults()['accent'], $colors['accent']);
    }

    public function testDerivedShadesAreDarkerThanTheBase(): void
    {
        $colors = cjw_brummen_design_colors();

        $brightness = static fn(string $hex): int => array_sum(cjw_brummen_hex_to_rgb($hex));

        $this->assertLessThan($brightness($colors['sage']), $brightness($colors['sage_deep']));
        $this->assertLessThan($brightness($colors['sage_deep']), $brightness($colors['forest']));
    }
}
