<?php
/**
 * Front page photo wall: taped polaroids from previous summers.
 *
 * The photos come from the cjw-summer-camp plugin settings (Website tab,
 * source of truth); the whole section is hidden when no photos are set.
 *
 * @package cjw-brummen
 */

$cjw_brummen_polaroids = cjw_brummen_polaroids();

if (! $cjw_brummen_polaroids) {
    return;
}
?>

<section class="fp-photos" data-reveal>
	<h2 class="fp-photos__title"><?php esc_html_e('Zo ziet kamp', 'cjw-brummen'); ?> <span class="squiggle"><?php esc_html_e('eruit', 'cjw-brummen'); ?></span></h2>
	<p class="fp-photos__lead"><?php esc_html_e('Geplakt in het kampplakboek — een greep uit vorige zomers.', 'cjw-brummen'); ?></p>
	<div class="fp-photos__grid">
		<?php foreach ($cjw_brummen_polaroids as $cjw_brummen_slot => $cjw_brummen_polaroid) : ?>
			<figure class="fp-polaroid fp-polaroid--<?php echo esc_attr((string) $cjw_brummen_slot); ?>">
				<span class="fp-polaroid__tape" aria-hidden="true"></span>
				<div class="fp-polaroid__frame">
					<?php echo wp_get_attachment_image($cjw_brummen_polaroid['image_id'], 'large', false, [ 'class' => 'fp-polaroid__img' ]); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() returns safe markup. ?>
				</div>
				<?php if ($cjw_brummen_polaroid['caption']) : ?>
					<figcaption class="fp-polaroid__caption"><?php echo esc_html($cjw_brummen_polaroid['caption']); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
	</div>
</section>
