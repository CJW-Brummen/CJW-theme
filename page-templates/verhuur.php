<?php

/**
 * Template Name: Verhuurpagina
 *
 * The page-header band and the editor's own copy, then the inventory: the
 * "Past het?" helper, the tents as cards with a to-scale footprint, and one
 * table with everything on it.
 *
 * The three sections are rendered from the cjw plugin's verhuurmateriaal
 * records, so a kampbeheerder changing "20" to "18" changes all three at once.
 * Prices are deliberately not among those records -- CJW quotes them per
 * enquiry -- so the contact address belongs in the page copy above, where the
 * editor owns it.
 *
 * @package cjw-brummen
 */

get_header();

while (have_posts()) :
    the_post();
    ?>

	<main id="primary" class="site-main">

		<header class="page-band">
			<svg class="page-band__doodle" width="150" height="110" viewBox="0 0 40 56" aria-hidden="true" focusable="false"><path d="M20 4 L8 22 H14 L5 38 H13 L4 52 H36 L27 38 H35 L26 22 H32 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path></svg>
			<div class="page-band__inner">
				<span class="page-band__kicker"><?php esc_html_e('Verhuur', 'cjw-brummen'); ?></span>
				<h1 class="page-band__title"><?php the_title(); ?></h1>
				<?php if (has_excerpt()) : ?>
					<p class="page-band__lead"><?php echo esc_html(get_the_excerpt()); ?></p>
				<?php endif; ?>
			</div>
			<svg class="page-band__wave" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--paper)" d="M0 60 Q 60 20 140 44 T 300 40 T 470 52 T 640 30 T 810 50 T 980 34 T 1150 52 T 1320 36 T 1440 48 L1440 90 L0 90 Z"></path></svg>
		</header>

		<div class="page-layout">
			<article id="post-<?php the_ID(); ?>" <?php post_class('page-layout__article'); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		</div>

		<div class="verhuur">
			<?php
            get_template_part('template-parts/verhuur/fit');
    get_template_part('template-parts/verhuur/cards');
    get_template_part('template-parts/verhuur/table');
    ?>
		</div>

	</main><!-- #primary -->

	<?php
endwhile;

get_footer();
