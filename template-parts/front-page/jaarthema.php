<?php
/**
 * Front page yearly theme teaser: accent-tinted band with the theme title,
 * description and the countdown card.
 *
 * Theme name, year and camp dates come from the cjw-summer-camp plugin
 * (source of truth); only the description is theme content.
 *
 * @package cjw-brummen
 */

$cjw_brummen_countdown = cjw_brummen_countdown();
$cjw_brummen_theme_year = cjw_brummen_theme_year();
?>

<section class="fp-theme" data-reveal>
	<svg class="fp-theme__wave" viewBox="0 0 1440 60" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--paper)" d="M0 0 L1440 0 L1440 22 Q 1330 46 1200 28 T 940 34 T 680 22 T 420 38 T 160 24 Q 70 18 0 34 Z"></path></svg>
	<div class="fp-theme__inner">
		<div class="fp-theme__copy">
			<span class="fp-theme__kicker"><?php esc_html_e('Dit jaar', 'cjw-brummen'); ?></span>
			<h2 class="fp-theme__title">
				<?php
                /* translators: %s: camp year. */
                printf(esc_html__('Thema %s:', 'cjw-brummen'), esc_html($cjw_brummen_theme_year));
?>
				<span class="fp-theme__accent"><?php echo esc_html(cjw_brummen_theme_name()); ?></span>
			</h2>
			<div class="fp-theme__text"><?php echo wp_kses_post(wpautop(cjw_brummen_theme_description())); ?></div>
			<svg class="fp-theme__pixels" width="120" height="30" viewBox="0 0 120 30" aria-hidden="true" focusable="false"><path d="M6 8h8v8h8V8h8v8h-8v8h-8v-8H6z" fill="currentColor" opacity="0.9"></path><path d="M66 8h8v8h8V8h8v8h-8v8h-8v-8h-8z" fill="currentColor" opacity="0.45"></path></svg>
		</div>
		<?php if (null !== $cjw_brummen_countdown) : ?>
		<div class="fp-theme__aside">
			<div class="fp-countdown"
				data-countdown
				data-target-ts="<?php echo esc_attr((string) $cjw_brummen_countdown['timestamp']); ?>"
				data-now-until-ts="<?php echo esc_attr((string) $cjw_brummen_countdown['now_until']); ?>"
				data-label-one="<?php esc_attr_e('nachtje slapen tot kamp!', 'cjw-brummen'); ?>"
				data-label-many="<?php esc_attr_e('nachtjes slapen tot kamp!', 'cjw-brummen'); ?>"
				data-value-now="<?php esc_attr_e('NU', 'cjw-brummen'); ?>"
				data-label-now="<?php esc_attr_e('We zitten nú op kamp in het bos!', 'cjw-brummen'); ?>"
				data-value-done="<?php echo esc_attr($cjw_brummen_countdown['done_value']); ?>"
				data-label-done="<?php esc_attr_e('Dat was ’m weer — tot volgend jaar!', 'cjw-brummen'); ?>">
				<div class="fp-countdown__value"><?php echo esc_html($cjw_brummen_countdown['value']); ?></div>
				<div class="fp-countdown__label"><?php echo esc_html($cjw_brummen_countdown['label']); ?></div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>
