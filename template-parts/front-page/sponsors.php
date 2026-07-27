<?php
/**
 * Front page sponsor section: thank-you note with sponsor logos on the
 * sandy band, plus the "become a sponsor" call-to-action.
 *
 * Sponsors come from the ACF "Sponsor" post type (source of truth): the
 * logo is the featured image, the link is the sponsor_link field. The
 * section itself always shows so new sponsors can be recruited.
 *
 * @package cjw-brummen
 */

$cjw_brummen_sponsors = cjw_brummen_sponsors();
?>

<section id="sponsoren" class="fp-sponsors" data-reveal>
	<svg class="fp-sponsors__wave" viewBox="0 0 1440 60" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--paper)" d="M0 0 L1440 0 L1440 26 Q 1300 44 1160 30 T 880 36 T 600 24 T 320 40 T 0 28 Z"></path></svg>
	<div class="fp-sponsors__inner">
		<h2 class="fp-sponsors__title"><?php esc_html_e('Bedankt!', 'cjw-brummen'); ?></h2>
		<p class="fp-sponsors__lead"><?php esc_html_e('Zonder onze sponsoren geen tenten, geen bus en geen marshmallows. Dankjewel aan alle bedrijven uit Brummen en omstreken.', 'cjw-brummen'); ?></p>
		<?php if ($cjw_brummen_sponsors) : ?>
			<div class="fp-sponsors__grid">
				<?php foreach (array_values($cjw_brummen_sponsors) as $cjw_brummen_index => $cjw_brummen_sponsor) : ?>
					<?php
                    $cjw_brummen_tag = $cjw_brummen_sponsor['url'] ? 'a' : 'div';
    $cjw_brummen_link_attrs = '';

    if ($cjw_brummen_sponsor['url']) {
        /* translators: %s: sponsor name. */
        $cjw_brummen_link_label = sprintf(__('%s (opent in nieuw tabblad)', 'cjw-brummen'), $cjw_brummen_sponsor['title']);
        $cjw_brummen_link_attrs = ' href="' . esc_url($cjw_brummen_sponsor['url']) . '" rel="sponsored noopener" target="_blank" aria-label="' . esc_attr($cjw_brummen_link_label) . '"';
    }
    ?>
					<<?php echo esc_html($cjw_brummen_tag); ?> class="fp-sponsor fp-sponsor--<?php echo esc_attr((string) (($cjw_brummen_index % 4) + 1)); ?>"<?php echo $cjw_brummen_link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above. ?>>
						<?php echo wp_get_attachment_image($cjw_brummen_sponsor['logo_id'], 'medium', false, [ 'class' => 'fp-sponsor__img' ]); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() returns safe markup. ?>
					</<?php echo esc_html($cjw_brummen_tag); ?>>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<p class="fp-sponsors__cta"><a href="#contact"><?php esc_html_e('Ook sponsor worden? Leuk! →', 'cjw-brummen'); ?></a></p>
	</div>
</section>
