<?php

/**
 * Title: Checklijst
 * Slug: cjw-brummen/checklijst
 * Categories: cjw-brummen
 * Description: Kop, korte intro en een lijst met vinkjes — een startpunt voor bijvoorbeeld de paklijst.
 * Viewport Width: 800
 *
 * @package cjw-brummen
 */

?>
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo wp_kses_post(__('Wat neem je <span class="squiggle">mee?</span>', 'cjw-brummen')); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__('Gebruik deze paklijst als startpunt — de vinkjes krijg je er gratis bij.', 'cjw-brummen'); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><?php echo esc_html__('Slaapzak, kussen en luchtbed of matje', 'cjw-brummen'); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__('Zaklamp met extra batterijen', 'cjw-brummen'); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__('Regenjas en laarzen', 'cjw-brummen'); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__('Zwemkleren en handdoek', 'cjw-brummen'); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__('Verkleedkleren voor het jaarthema', 'cjw-brummen'); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->
