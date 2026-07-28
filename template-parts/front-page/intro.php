<?php
/**
 * Front page introduction: welcome copy with tent/tree doodles and the
 * organically masked camp photo, followed by the dashed path divider.
 *
 * @package cjw-brummen
 */

$cjw_brummen_intro_copy = cjw_brummen_home_intro();
?>

<section class="fp-intro" data-reveal>
	<div class="fp-intro__copy">
		<h2 class="fp-intro__title"><?php echo cjw_brummen_squiggle_title($cjw_brummen_intro_copy['title']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- cjw_brummen_squiggle_title() returns escaped HTML. ?></h2>
		<?php echo wp_kses_post(wpautop($cjw_brummen_intro_copy['text'])); ?>
		<div class="fp-intro__doodles" aria-hidden="true">
			<svg width="34" height="48" viewBox="0 0 40 56" focusable="false"><path d="M20 4 L8 22 H14 L5 38 H13 L4 52 H36 L27 38 H35 L26 22 H32 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path></svg>
			<svg class="fp-intro__doodle-apricot" width="26" height="38" viewBox="0 0 40 56" focusable="false"><path d="M20 4 L8 22 H14 L5 38 H13 L4 52 H36 L27 38 H35 L26 22 H32 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path></svg>
			<svg width="44" height="34" viewBox="0 0 48 40" focusable="false"><path d="M4 36 L24 4 L44 36 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path><path d="M19 36 L24 21 L29 36" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path></svg>
		</div>
	</div>
	<div class="fp-intro__media">
		<div class="fp-intro__frame" aria-hidden="true"></div>
		<div class="fp-intro__blob">
			<?php
            $cjw_brummen_intro_id = cjw_brummen_intro_image_id();
$cjw_brummen_intro_img = $cjw_brummen_intro_id ? wp_get_attachment_image($cjw_brummen_intro_id, 'large', false, [ 'class' => 'fp-intro__img' ]) : '';

if ($cjw_brummen_intro_img) {
    echo $cjw_brummen_intro_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() returns safe markup.
} else {
    ?>
				<span class="fp-intro__empty" aria-hidden="true">
					<svg width="72" height="56" viewBox="0 0 48 40" focusable="false"><path d="M4 36 L24 4 L44 36 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path><path d="M19 36 L24 21 L29 36" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path></svg>
				</span>
				<?php
}
?>
		</div>
	</div>
</section>

<div class="fp-path" aria-hidden="true">
	<svg width="220" height="90" viewBox="0 0 220 90" focusable="false"><path d="M20 10 Q 80 40 110 44 T 200 78" fill="none" stroke="var(--sage)" stroke-width="3.5" stroke-dasharray="2 14" stroke-linecap="round"></path><ellipse cx="26" cy="14" rx="4" ry="6" fill="var(--sage)" transform="rotate(30 26 14)"></ellipse><ellipse cx="196" cy="72" rx="4" ry="6" fill="var(--sage)" transform="rotate(50 196 72)"></ellipse></svg>
</div>
