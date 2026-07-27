<?php
/**
 * Front page navigation cards: Zomerkamp, Praktische info and Inschrijven.
 *
 * @package cjw-brummen
 */

$cjw_brummen_card_year = cjw_brummen_theme_year();
$cjw_brummen_cta = cjw_brummen_signup_cta();
?>

<section class="fp-cards" data-reveal>
	<h2 class="screen-reader-text"><?php esc_html_e('Snel naar', 'cjw-brummen'); ?></h2>
	<div class="fp-cards__grid">
		<a class="fp-card fp-card--camp" href="<?php echo esc_url(cjw_brummen_card_link('zomerkamp')); ?>">
			<svg class="fp-card__icon" width="52" height="42" viewBox="0 0 48 40" aria-hidden="true" focusable="false"><path d="M4 36 L24 4 L44 36 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path><path d="M19 36 L24 21 L29 36" fill="none" stroke="var(--apricot)" stroke-width="3" stroke-linejoin="round"></path></svg>
			<h3 class="fp-card__title"><?php esc_html_e('Zomerkamp', 'cjw-brummen'); ?></h3>
			<p><?php esc_html_e('Hoe ziet een kampweek eruit? Alles over de leeftijdsgroepen, het programma en het leven tussen de tenten.', 'cjw-brummen'); ?></p>
			<span class="fp-card__more"><?php esc_html_e('Lees meer →', 'cjw-brummen'); ?></span>
		</a>

		<a class="fp-card fp-card--info" href="<?php echo esc_url(cjw_brummen_card_link('info')); ?>">
			<svg class="fp-card__icon" width="46" height="46" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><circle cx="24" cy="24" r="19" fill="none" stroke="currentColor" stroke-width="3"></circle><path d="M30 16 L26 27 L18 32 L22 21 Z" fill="none" stroke="var(--sage)" stroke-width="3" stroke-linejoin="round"></path></svg>
			<h3 class="fp-card__title"><?php esc_html_e('Praktische info', 'cjw-brummen'); ?></h3>
			<p><?php esc_html_e('Paklijst, data, kosten, vervoer en alle antwoorden voor (bezorgde) ouders. Zo kom je goed voorbereid aan.', 'cjw-brummen'); ?></p>
			<span class="fp-card__more"><?php esc_html_e('Lees meer →', 'cjw-brummen'); ?></span>
		</a>

		<a class="fp-card fp-card--signup" href="<?php echo esc_url($cjw_brummen_cta['url']); ?>">
			<svg class="fp-card__icon" width="46" height="46" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M10 38 L34 10 L40 16 L16 44 L8 46 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path><path d="M30 14 L36 20" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path></svg>
			<h3 class="fp-card__title"><?php esc_html_e('Inschrijven', 'cjw-brummen'); ?></h3>
			<p>
				<?php
                /* translators: %s: camp year. */
                printf(esc_html__('Vol = vol! Schrijf je snel in voor kamp %s en verzeker jezelf van een plekje in het bos.', 'cjw-brummen'), esc_html($cjw_brummen_card_year));
?>
			</p>
			<span class="fp-card__more"><?php esc_html_e('Schrijf je in →', 'cjw-brummen'); ?></span>
		</a>
	</div>
</section>
