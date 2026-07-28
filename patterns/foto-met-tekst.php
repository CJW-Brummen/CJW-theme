<?php

/**
 * Title: Foto met tekst
 * Slug: cjw-brummen/foto-met-tekst
 * Categories: cjw-brummen
 * Description: Foto naast een kop met onderstreping, een alinea en een lijst met vinkjes.
 * Viewport Width: 1000
 *
 * @package cjw-brummen
 */

?>
<!-- wp:media-text -->
<div class="wp-block-media-text is-stacked-on-mobile"><figure class="wp-block-media-text__media"></figure><div class="wp-block-media-text__content"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo wp_kses_post(__('Een dag op <span class="squiggle">kamp</span>', 'cjw-brummen')); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__('Van het ochtendlied bij de vlag tot marshmallows boven het kampvuur: op kamp zit geen minuut stil.', 'cjw-brummen'); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><?php echo esc_html__('Bosspellen met je tentgroep', 'cjw-brummen'); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__('Koken boven het kampvuur', 'cjw-brummen'); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__('Bonte avond met het hele kamp', 'cjw-brummen'); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div></div>
<!-- /wp:media-text -->
