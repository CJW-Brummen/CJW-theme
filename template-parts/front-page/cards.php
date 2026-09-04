<?php
/**
 * Front page navigation cards: Zomerkamp, Praktische info and Inschrijven.
 *
 * The card copy comes from the cjw-summer-camp plugin settings (source of
 * truth); the signup card follows the registration state.
 *
 * @package cjw-brummen
 */

$cjw_brummen_cards = cjw_brummen_cards_copy();
$cjw_brummen_cta = cjw_brummen_signup_cta();

// The whole card is the link, so without a form page to go to it stops being
// one: a static card rather than a link to nowhere. Closed registration is a
// static card too, wearing the muted chip and the reason on hover.
$cjw_brummen_signup_is_link = $cjw_brummen_cta['open'] && '' !== $cjw_brummen_cta['url'];
$cjw_brummen_signup_more_class = $cjw_brummen_cta['open'] ? 'fp-card__more' : 'fp-card__more btn--muted';
$cjw_brummen_signup_more_label = '';

if (! $cjw_brummen_cta['open']) {
    $cjw_brummen_signup_more_label = __('Inschrijving gesloten', 'cjw-brummen');
} elseif ($cjw_brummen_signup_is_link) {
    $cjw_brummen_signup_more_label = __('Schrijf je in →', 'cjw-brummen');
}
?>

<section class="fp-cards" data-reveal>
	<h2 class="screen-reader-text"><?php esc_html_e('Snel naar', 'cjw-brummen'); ?></h2>
	<div class="fp-cards__grid">
		<a class="fp-card fp-card--camp" href="<?php echo esc_url(cjw_brummen_card_link('zomerkamp')); ?>">
			<svg class="fp-card__icon" width="52" height="42" viewBox="0 0 48 40" aria-hidden="true" focusable="false"><path d="M4 36 L24 4 L44 36 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path><path d="M19 36 L24 21 L29 36" fill="none" stroke="var(--apricot)" stroke-width="3" stroke-linejoin="round"></path></svg>
			<h3 class="fp-card__title"><?php echo esc_html($cjw_brummen_cards['zomerkamp']['title']); ?></h3>
			<p><?php echo esc_html($cjw_brummen_cards['zomerkamp']['text']); ?></p>
			<span class="fp-card__more"><?php esc_html_e('Lees meer →', 'cjw-brummen'); ?></span>
		</a>

		<a class="fp-card fp-card--info" href="<?php echo esc_url(cjw_brummen_card_link('info')); ?>">
			<svg class="fp-card__icon" width="46" height="46" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><circle cx="24" cy="24" r="19" fill="none" stroke="currentColor" stroke-width="3"></circle><path d="M30 16 L26 27 L18 32 L22 21 Z" fill="none" stroke="var(--sage)" stroke-width="3" stroke-linejoin="round"></path></svg>
			<h3 class="fp-card__title"><?php echo esc_html($cjw_brummen_cards['info']['title']); ?></h3>
			<p><?php echo esc_html($cjw_brummen_cards['info']['text']); ?></p>
			<span class="fp-card__more"><?php esc_html_e('Lees meer →', 'cjw-brummen'); ?></span>
		</a>

		<?php if ($cjw_brummen_signup_is_link) : ?>
		<a class="fp-card fp-card--signup" href="<?php echo esc_url($cjw_brummen_cta['url']); ?>">
		<?php elseif (! $cjw_brummen_cta['open']) : ?>
		<div class="fp-card fp-card--signup fp-card--static" title="<?php echo esc_attr(cjw_brummen_registration_closed_text()); ?>">
		<?php else : ?>
		<div class="fp-card fp-card--signup fp-card--static">
		<?php endif; ?>
			<svg class="fp-card__icon" width="46" height="46" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M10 38 L34 10 L40 16 L16 44 L8 46 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path><path d="M30 14 L36 20" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path></svg>
			<h3 class="fp-card__title"><?php echo esc_html($cjw_brummen_cards['signup']['title']); ?></h3>
			<p><?php echo esc_html($cjw_brummen_cards['signup']['text']); ?></p>
			<?php if ('' !== $cjw_brummen_signup_more_label) : ?>
			<span class="<?php echo esc_attr($cjw_brummen_signup_more_class); ?>"><?php echo esc_html($cjw_brummen_signup_more_label); ?></span>
			<?php endif; ?>
		<?php echo $cjw_brummen_signup_is_link ? '</a>' : '</div>'; ?>
	</div>
</section>
