<?php
/**
 * Front page hero: full-bleed yearly theme photo with title, date badge,
 * signup button and the treeline/wave transition into the page.
 *
 * All content comes from the cjw-summer-camp plugin settings (source of
 * truth); the bundled illustration is the fallback hero image.
 *
 * @package cjw-brummen
 */

$cjw_brummen_hero_id = cjw_brummen_hero_image_id();
$cjw_brummen_badge = cjw_brummen_hero_badge_text();
$cjw_brummen_cta = cjw_brummen_signup_cta();
$cjw_brummen_cta_class = $cjw_brummen_cta['open'] ? 'btn btn--hero' : 'btn btn--hero btn--muted';
$cjw_brummen_cta_label = $cjw_brummen_cta['open'] ? $cjw_brummen_cta['label'] : __('Inschrijving gesloten', 'cjw-brummen');
$cjw_brummen_hero_alt = cjw_brummen_hero_image_alt();
$cjw_brummen_secondary = cjw_brummen_hero_secondary_cta();
?>

<section class="fp-hero">
	<div class="fp-hero__media">
		<?php
        /*
         * The hero is full-bleed, so WordPress's own sizes -- 100vw up to the
         * original's 2560px -- is honest about width and expensive because of
         * it: a retina desktop fetches the 2560px original at 868 KB, which is
         * over 90% of the page.
         *
         * A plain width cap cannot fix that. `sizes` is a CSS-pixel width and
         * the browser still multiplies by device pixel ratio, so on a 1440px
         * retina screen "100vw" already means 2880 device pixels and any
         * (min-width: N) branch simply never matches. The only honest lever is
         * to cap on density: on a display of 1.5dppx or more the hero is
         * advertised at 1024 CSS px, which resolves to the 2048w file (583 KB)
         * instead of the 2560w original. Every 1x display and every phone
         * fetches exactly the same file as before.
         *
         * The alt text is passed rather than left to the attachment, so the
         * organiser's own wording on the settings screen is what gets read out.
         */
        $cjw_brummen_hero_attr = [
            'class' => 'fp-hero__img',
            'sizes' => '(min-width: 1024px) and (min-resolution: 1.5dppx) 1024px, 100vw',
        ];

if ('' !== $cjw_brummen_hero_alt) {
    $cjw_brummen_hero_attr['alt'] = $cjw_brummen_hero_alt;
}

        $cjw_brummen_hero_img = $cjw_brummen_hero_id ? wp_get_attachment_image($cjw_brummen_hero_id, 'full', false, $cjw_brummen_hero_attr) : '';

if ($cjw_brummen_hero_img) {
    echo $cjw_brummen_hero_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() returns safe markup.
} else {
    printf(
        '<img class="fp-hero__img" src="%s" alt="">',
        esc_url(get_template_directory_uri() . '/assets/images/hero-illustratie.svg')
    );
}
?>
	</div>

	<div class="fp-hero__overlay">
		<div class="fp-hero__container">
			<div class="fp-hero__content">
				<?php if ($cjw_brummen_badge) : ?>
					<span class="fp-hero__badge"><span aria-hidden="true">✏</span> <?php echo esc_html($cjw_brummen_badge); ?></span>
				<?php endif; ?>
				<h1 class="fp-hero__title"><?php echo esc_html(cjw_brummen_hero_title()); ?></h1>
				<p class="fp-hero__lead"><?php echo esc_html(cjw_brummen_hero_subtitle()); ?></p>
				<div class="fp-hero__actions">
					<a class="<?php echo esc_attr($cjw_brummen_cta_class); ?>" href="<?php echo esc_url($cjw_brummen_cta['url']); ?>"><?php echo esc_html($cjw_brummen_cta_label); ?></a>
					<?php if (null !== $cjw_brummen_secondary) : ?>
						<a class="btn btn--hero btn--ghost" href="<?php echo esc_url($cjw_brummen_secondary['url']); ?>"><?php echo esc_html($cjw_brummen_secondary['label']); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<svg class="fp-hero__treeline" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--sage)" d="M0 120 L0 78 L30 40 L52 74 L70 26 L96 70 L118 44 L138 76 L160 22 L186 72 L210 48 L232 78 L258 30 L282 72 L306 50 L326 78 L352 24 L378 70 L400 44 L422 76 L448 34 L472 72 L494 52 L516 80 L540 26 L566 72 L590 46 L612 76 L636 30 L662 70 L686 50 L708 78 L732 22 L758 72 L782 44 L804 76 L830 32 L854 70 L878 52 L900 80 L924 26 L950 72 L974 46 L996 76 L1020 30 L1046 70 L1070 50 L1092 78 L1116 24 L1142 72 L1166 44 L1188 76 L1214 34 L1238 70 L1262 52 L1284 80 L1308 26 L1334 72 L1358 46 L1380 76 L1404 36 L1428 70 L1440 60 L1440 120 Z"></path></svg>
	<svg class="fp-hero__wave" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--paper)" d="M0 60 Q 60 20 140 44 T 300 40 T 470 52 T 640 30 T 810 50 T 980 34 T 1150 52 T 1320 36 T 1440 48 L1440 90 L0 90 Z"></path></svg>
</section>
