<?php

declare(strict_types=1);

/**
 * The theme's documented contract: every plugin-backed helper degrades to a
 * design default when the cjw plugin is not active, so the theme never fatals
 * on a site where the plugin is missing or deactivated.
 */
final class BridgeTest extends CJW_Brummen_TestCase
{
    public function testCampIsNullWithoutThePlugin(): void
    {
        $this->assertNull(cjw_brummen_camp());
    }

    public function testThemeNameFallsBackToTheDefaultTitle(): void
    {
        $this->assertSame(
            cjw_brummen_front_page_defaults()['theme_title'],
            cjw_brummen_theme_name()
        );
    }

    public function testThemeYearFallsBackToTheCountdownYear(): void
    {
        $expected = substr(cjw_brummen_front_page_defaults()['countdown_date'], 0, 4);

        $this->assertSame($expected, cjw_brummen_theme_year());
    }

    public function testHeroTitleFallsBackToTheDefault(): void
    {
        $this->assertSame(
            cjw_brummen_front_page_defaults()['hero_title'],
            cjw_brummen_hero_title()
        );
    }

    public function testCampSettingReturnsTheGivenDefaultForAnUnknownMethod(): void
    {
        $this->assertSame(
            'fallback',
            cjw_brummen_camp_setting('getSomethingThatDoesNotExist', 'fallback')
        );
    }

    public function testRegistrationIsOpenWithoutThePlugin(): void
    {
        $this->assertTrue(cjw_brummen_registration_open());
    }
}
