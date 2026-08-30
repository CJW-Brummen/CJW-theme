<?php

/**
 * Template Name: Verhuurpagina
 *
 * The page-header band and the editor's own content, and nothing else.
 *
 * This template used to render the three inventory sections itself, always in
 * the same order, always under the same headings, with the page's own copy
 * stuck above all of them. They are blocks now -- CJW Past het?, CJW
 * Verhuurkaarten and CJW Verhuurtabel -- so the editor decides what appears,
 * in what order, and what is written between them.
 *
 * What is left is the one thing a template still has to decide: this page does
 * not end in the signup call to action that page.php appends, because the
 * action here is an e-mail about hiring a tent, not registering a child for
 * camp.
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

	</main><!-- #primary -->

	<?php
endwhile;

get_footer();
