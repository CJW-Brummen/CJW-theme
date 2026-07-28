<?php
/**
 * The template for displaying 404 pages (not found), in the sage
 * page-band chrome of the inner pages.
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package cjw-brummen
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<header class="page-band">
				<svg class="page-band__doodle" width="150" height="110" viewBox="0 0 40 56" aria-hidden="true" focusable="false"><path d="M20 4 L8 22 H14 L5 38 H13 L4 52 H36 L27 38 H35 L26 22 H32 Z" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"></path></svg>
				<div class="page-band__inner">
					<span class="page-band__kicker"><?php esc_html_e('Foutcode 404', 'cjw-brummen'); ?></span>
					<h1 class="page-band__title"><?php esc_html_e('Oeps, dit pad loopt dood in het bos', 'cjw-brummen'); ?></h1>
					<p class="page-band__lead"><?php esc_html_e('De pagina die je zoekt bestaat niet (meer) of is verhuisd. Loop een stukje terug, of zoek hieronder verder naar wat je nodig hebt.', 'cjw-brummen'); ?></p>
				</div>
				<svg class="page-band__wave" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="var(--paper)" d="M0 60 Q 60 20 140 44 T 300 40 T 470 52 T 640 30 T 810 50 T 980 34 T 1150 52 T 1320 36 T 1440 48 L1440 90 L0 90 Z"></path></svg>
			</header>

			<div class="error-404__actions">
				<?php get_search_form(); ?>
				<a class="btn" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Terug naar de homepage', 'cjw-brummen'); ?></a>
			</div>
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
