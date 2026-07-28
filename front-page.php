<?php
/**
 * The front page template: the "CJW Homepage" design.
 *
 * The front page is a static page composed of the six cjw/* dynamic blocks
 * (cjw/hero, cjw/intro, cjw/jaarthema, cjw/cards, cjw/photos, cjw/sponsors),
 * registered in inc/blocks.php. Each block renders its template part in
 * template-parts/front-page/, so the markup matches the classic design while
 * the sections are editable (reorder/remove) in the block editor. All camp
 * data still comes from the cjw-* plugins (source of truth); this template
 * only provides the outer shell and the loop.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package cjw-brummen
 */

get_header();
?>

	<main id="primary" class="site-main front-page">

		<?php
		if (have_posts()) {
			while (have_posts()) {
				the_post();
				the_content();
			}
		}
		?>

	</main><!-- #primary -->

<?php
get_footer();
