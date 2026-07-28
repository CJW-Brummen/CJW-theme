<?php

/**
 * Title: Oproep-banner
 * Slug: cjw-brummen/cta-band
 * Categories: cjw-brummen
 * Description: Inschrijf-oproep in een omkaderd vlak met titel, tekst en knop, zoals onderaan elke pagina.
 * Viewport Width: 1200
 *
 * @package cjw-brummen
 */

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
<div class="wp-block-button btn"><a class="wp-block-button__link wp-element-button" href="#inschrijven"><?php echo esc_html__('Schrijf je in!', 'cjw-brummen'); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
