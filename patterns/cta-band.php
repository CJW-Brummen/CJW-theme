<?php

/**
 * Title: Oproep-banner
 * Slug: cjw-brummen/cta-band
 * Categories: cjw-brummen
 * Description: Inschrijf-oproep in een omkaderd vlak met titel, tekst en knop, zoals onderaan elke pagina.
 * Viewport Width: 1200
 *
 * The button's address is read from the plugin when the pattern is inserted
 * and then lives in the page like any other block: moving the form to another
 * page later means editing the button here by hand. The dynamic version of
 * this band, which follows the form page and the registration state on its
 * own, is what page.php prints under every page already.
 *
 * @package cjw-brummen
 */

$cjw_brummen_pattern_signup = cjw_brummen_signup_cta();
$cjw_brummen_pattern_signup_url = '' !== $cjw_brummen_pattern_signup['url'] ? $cjw_brummen_pattern_signup['url'] : home_url('/');
?>
<!-- wp:group {"tagName":"section","className":"page-cta"} -->
<section class="wp-block-group page-cta"><!-- wp:group {"className":"page-cta__box"} -->
<div class="wp-block-group page-cta__box"><!-- wp:group -->
<div class="wp-block-group"><!-- wp:heading {"className":"page-cta__title"} -->
<h2 class="wp-block-heading page-cta__title"><?php echo esc_html__('Zin gekregen?', 'cjw-brummen'); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"page-cta__text"} -->
<p class="page-cta__text"><?php echo esc_html__('Vol = vol, dus wacht niet te lang.', 'cjw-brummen'); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"btn"} -->
<div class="wp-block-button btn"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($cjw_brummen_pattern_signup_url); ?>"><?php echo esc_html__('Schrijf je in!', 'cjw-brummen'); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
