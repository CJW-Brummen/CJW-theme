<?php
/**
 * The front page template: the "CJW Homepage" design.
 *
 * Shows the hero with the yearly theme photo, the introduction, the yearly
 * theme teaser with countdown, the navigation cards, the polaroid photo wall
 * and the sponsor section.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package cjw-brummen
 */

get_header();
?>

	<main id="primary" class="site-main front-page">

		<?php get_template_part('template-parts/front-page/hero'); ?>
		<?php get_template_part('template-parts/front-page/intro'); ?>
		<?php get_template_part('template-parts/front-page/jaarthema'); ?>
		<?php get_template_part('template-parts/front-page/cards'); ?>
		<?php get_template_part('template-parts/front-page/photos'); ?>
		<?php get_template_part('template-parts/front-page/sponsors'); ?>

	</main><!-- #primary -->

<?php
get_footer();
