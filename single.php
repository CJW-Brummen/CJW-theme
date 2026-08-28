<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cjw-brummen
 */

get_header();
?>

	<main id="primary" class="site-main site-main--plain">

		<?php
        while (have_posts()) :
            the_post();

            get_template_part('template-parts/content', get_post_type());

            the_post_navigation(
                [
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Vorige:', 'cjw-brummen') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Volgende:', 'cjw-brummen') . '</span> <span class="nav-title">%title</span>',
                ]
            );

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile; // End of the loop.
?>

	</main><!-- #main -->

<?php
get_footer();
