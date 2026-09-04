<?php
/**
 * The template for displaying all pages: the "CJW Pagina Template" design.
 *
 * Sage page-header band with the page title, the entry content in the
 * design typography (with an optional featured-image blob aside) and the
 * signup call-to-action band.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
				<h1 class="page-band__title"><?php the_title(); ?></h1>
				<?php if (has_excerpt()) : ?>
					<p class="page-band__lead"><?php echo esc_html(get_the_excerpt()); ?></p>
				<?php endif; ?>
			</div>
			<svg class="page-band__wave" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--paper)" d="M0 60 Q 60 20 140 44 T 300 40 T 470 52 T 640 30 T 810 50 T 980 34 T 1150 52 T 1320 36 T 1440 48 L1440 90 L0 90 Z"></path></svg>
		</header>

		<div class="page-layout">
			<?php if (has_post_thumbnail()) : ?>
				<aside class="page-layout__aside">
					<div class="page-blob">
						<div class="page-blob__frame" aria-hidden="true"></div>
						<div class="page-blob__media"><?php the_post_thumbnail('large'); ?></div>
					</div>
				</aside>
			<?php endif; ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('page-layout__article'); ?>>
				<div class="entry-content">
					<?php
                    the_content();

    wp_link_pages(
        [
            'before' => '<div class="page-links">' . esc_html__('Pagina&rsquo;s:', 'cjw-brummen'),
            'after' => '</div>',
        ]
    );
    ?>
				</div>
			</article>
		</div>

		<?php
    $cjw_brummen_cta_band = cjw_brummen_cta_band();
    ?>
		<section class="page-cta">
			<div class="page-cta__box">
				<div>
					<h2 class="page-cta__title"><?php echo esc_html($cjw_brummen_cta_band['title']); ?></h2>
					<p class="page-cta__text"><?php echo esc_html($cjw_brummen_cta_band['text']); ?></p>
				</div>
				<?php echo cjw_brummen_signup_button('btn btn--hero'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- cjw_brummen_signup_button() returns escaped HTML. ?>
			</div>
		</section>

		<?php
        // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) :
        comments_template();
    endif;
    ?>

	</main><!-- #primary -->

	<?php
endwhile;

get_footer();
