<?php

declare(strict_types=1);

/**
 * The signup button, in its three states.
 *
 * It used to be one thing: a link to `#inschrijven`, an anchor no template has
 * ever carried, so the primary call to action on every page of the site
 * scrolled nowhere -- and when registration was closed it was still a live
 * link to that nowhere. The plugin now says where the form page is, and the
 * theme renders a link, a muted non-link, or nothing, from that one answer.
 */
final class SignupButtonTest extends CJW_Brummen_TestCase
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
        unset($GLOBALS['cjw_brummen_test_camp']);

        parent::tearDown();
    }

    public function testAnOpenRegistrationWithAFormPageIsALink(): void
    {
        $this->withCamp([ 'primary_cta' => [ 'label' => 'Meld je aan', 'url' => 'https://example.test/inschrijven/' ] ]);

        $html = cjw_brummen_signup_button('btn btn--hero');

        $this->assertSame('<a class="btn btn--hero" href="https://example.test/inschrijven/">Meld je aan</a>', $html);
    }

    public function testAClosedRegistrationIsNotALink(): void
    {
        $this->withCamp([
            'primary_cta' => [ 'label' => 'Meld je aan', 'url' => 'https://example.test/inschrijven/' ],
            'registration_open' => false,
            'registration_closed_text' => '<p>Vol! Mail ons voor de <strong>wachtlijst</strong>.</p>',
        ]);

        $html = cjw_brummen_signup_button('btn btn--hero');

        $this->assertStringNotContainsString('<a ', $html);
        $this->assertStringNotContainsString('href', $html);
        $this->assertStringContainsString('class="btn btn--hero btn--muted"', $html);
        $this->assertStringContainsString('Inschrijving gesloten', $html);
        $this->assertStringContainsString('title="Vol! Mail ons voor de wachtlijst."', $html, 'the reason, with the tags off');
        $this->assertStringContainsString('<span class="screen-reader-text">: Vol! Mail ons voor de wachtlijst.</span>', $html);
    }

    public function testAClosedRegistrationWithoutACustomTextStillExplainsItself(): void
    {
        $this->withCamp([ 'registration_open' => false ]);

        $this->assertStringContainsString('title="De inschrijving voor het zomerkamp is gesloten."', cjw_brummen_signup_button('btn'));
    }

    /**
     * Open, and nowhere to send anyone: no button. Site Health reports this
     * case on the plugin side; a link to nowhere would only hide it.
     */
    public function testAnOpenRegistrationWithoutAFormPageShowsNoButton(): void
    {
        $this->withCamp([ 'primary_cta' => [ 'label' => 'Meld je aan', 'url' => '' ] ]);

        $this->assertSame('', cjw_brummen_signup_button('btn'));
    }

    public function testThePluginsLabelIsUsedAndTheThemesIsTheFallback(): void
    {
        $this->withCamp([ 'primary_cta' => [ 'label' => 'Doe mee!', 'url' => 'https://example.test/x/' ] ]);
        $this->assertSame('Doe mee!', cjw_brummen_signup_cta()['label']);

        $this->withCamp([ 'primary_cta' => [ 'label' => '', 'url' => 'https://example.test/x/' ] ]);
        $this->assertSame('Schrijf je in!', cjw_brummen_signup_cta()['label']);
    }

    /**
     * The regression this whole change exists for: no template may link to the
     * anchor again. Looks for the anchor as a string or an href, so a comment
     * explaining the history is still allowed to name it.
     */
    public function testNoTemplateLinksToTheAnchorThatExistsNowhere(): void
    {
        $root = dirname(__DIR__);
        $offenders = [];
        $forms = [ "'#inschrijven'", '"#inschrijven"', 'href="#inschrijven"' ];

        foreach (new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
            static fn(SplFileInfo $file): bool => ! in_array($file->getFilename(), [ 'vendor', 'node_modules', 'tests', '.git' ], true)
        )) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $source = (string) file_get_contents($file->getPathname());

            foreach ($forms as $form) {
                if (str_contains($source, $form)) {
                    $offenders[] = substr($file->getPathname(), strlen($root) + 1);
                    break;
                }
            }
        }

        $this->assertSame([], $offenders);
    }
}
