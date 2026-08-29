<?php

declare(strict_types=1);

/**
 * What the theme does with a plugin that is installed but has blank fields.
 *
 * This is the half of the contract nothing covered. The theme treated "the
 * plugin says nothing" the same as "there is no plugin", and answered both with
 * its own 2026 design defaults — so a site whose camp dates were blank told
 * visitors the camp ran from 18 to 25 July and was already over.
 *
 * Blank is exactly the state the plugin's "Nieuw kampjaar starten" action
 * leaves behind, so this is the ordinary condition of the site every autumn.
 */
final class CampPresentTest extends CJW_Brummen_TestCase
{
    /**
     * @param array<string, mixed> $values
     */
    private function withCamp(array $values): void
    {
        $GLOBALS['cjw_brummen_test_camp'] = new FakeCamp($values);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['cjw_brummen_test_camp'], $GLOBALS['cjw_brummen_test_attachments']);

        parent::tearDown();
    }

    public function testTheDateStickerIsEmptyWhenNoDatesAreSet(): void
    {
        $this->withCamp([ 'date_range' => '' ]);

        $this->assertSame('', cjw_brummen_hero_badge_text());
    }

    public function testTheDateStickerShowsTheCampsOwnRange(): void
    {
        $this->withCamp([ 'date_range' => '10 t/m 17 juli' ]);

        $this->assertSame('10 t/m 17 juli', cjw_brummen_hero_badge_text());
    }

    /**
     * The design default is a fixed date in 2026. Reaching it from a running
     * plugin meant the homepage counted down to a day in the past and then
     * announced the camp was finished.
     */
    public function testThereIsNoCountdownWithoutAStartDate(): void
    {
        $this->withCamp([ 'start_date' => '' ]);

        $this->assertNull(cjw_brummen_countdown());
    }

    public function testTheCountdownRunsToTheCampsOwnStartDate(): void
    {
        $this->withCamp([ 'start_date' => '2099-07-10', 'end_date' => '2099-07-17' ]);

        $countdown = cjw_brummen_countdown();

        $this->assertIsArray($countdown);
        $this->assertSame('2099-07-10', gmdate('Y-m-d', $countdown['timestamp']));
    }

    /**
     * Without a plugin the design defaults are still right — the theme has to
     * look like something on a site that has never had the plugin.
     */
    public function testTheDesignDefaultsSurviveWhenThereIsNoPluginAtAll(): void
    {
        $this->assertNotSame('', cjw_brummen_hero_badge_text());
        $this->assertIsArray(cjw_brummen_countdown());
    }

    public function testAPolaroidWhosePhotoWasDeletedIsDropped(): void
    {
        $GLOBALS['cjw_brummen_test_attachments'] = [ 11 ];
        $this->withCamp([
            'polaroids' => [
                1 => [ 'image_id' => 11, 'caption' => 'Kampvuur' ],
                2 => [ 'image_id' => 99, 'caption' => 'Weg uit de mediabibliotheek' ],
            ],
        ]);

        $polaroids = cjw_brummen_polaroids();

        $this->assertCount(1, $polaroids, 'a slot pointing at a deleted attachment rendered an empty taped frame');
        $this->assertSame(11, array_values($polaroids)[0]['image_id']);
    }

    public function testThePhotoWallSurvivesEveryPhotoBeingDeleted(): void
    {
        $GLOBALS['cjw_brummen_test_attachments'] = [];
        $this->withCamp([ 'polaroids' => [ 1 => [ 'image_id' => 99, 'caption' => 'Weg' ] ] ]);

        $this->assertSame([], cjw_brummen_polaroids());
    }

    public function testTheOrganisersAltTextIsWhatTheThemeAsksFor(): void
    {
        $this->withCamp([ 'hero_image_alt' => 'Kinderen bij het kampvuur' ]);

        $this->assertSame('Kinderen bij het kampvuur', cjw_brummen_hero_image_alt());
    }

    /**
     * The scrim is the organiser's control, but it has a floor: hero text sits
     * on an unpredictable photograph, and 0% would put it back on the picture.
     */
    public function testTheScrimNeverDropsBelowLegible(): void
    {
        $this->withCamp([ 'hero_overlay_opacity' => 0 ]);
        $this->assertGreaterThanOrEqual(0.45, cjw_brummen_hero_overlay_alpha());

        $this->withCamp([ 'hero_overlay_opacity' => 80 ]);
        $this->assertEqualsWithDelta(0.80, cjw_brummen_hero_overlay_alpha(), 0.001);
    }

    public function testAnOutOfRangeOverlayIsClamped(): void
    {
        $this->withCamp([ 'hero_overlay_opacity' => 400 ]);

        $this->assertEqualsWithDelta(1.0, cjw_brummen_hero_overlay_alpha(), 0.001);
    }

    /**
     * The plugin substitutes its own placeholder for a blank field, and that
     * placeholder points at #meer — an anchor this theme has never contained.
     */
    public function testTheShippedPlaceholderDoesNotBecomeAButton(): void
    {
        $this->withCamp([ 'secondary_cta' => [ 'label' => 'Lees meer', 'url' => '#meer' ] ]);

        $this->assertNull(cjw_brummen_hero_secondary_cta());
    }

    public function testARealSecondButtonIsRendered(): void
    {
        $this->withCamp([ 'secondary_cta' => [ 'label' => 'Bekijk het programma', 'url' => '/zomerkamp/' ] ]);

        $this->assertSame(
            [ 'label' => 'Bekijk het programma', 'url' => '/zomerkamp/' ],
            cjw_brummen_hero_secondary_cta()
        );
    }

    public function testAHalfFilledSecondButtonIsNotRendered(): void
    {
        $this->withCamp([ 'secondary_cta' => [ 'label' => 'Bekijk', 'url' => '' ] ]);

        $this->assertNull(cjw_brummen_hero_secondary_cta());
    }
}
